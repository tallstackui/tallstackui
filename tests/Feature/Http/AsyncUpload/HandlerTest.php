<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TallStackUi\Components\Form\Upload\Async\Component;
use TallStackUi\Http\AsyncUpload\AsyncUploadException;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadCompleted;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadFailed;
use TallStackUi\Http\AsyncUpload\Events\AsyncUploadStarted;
use TallStackUi\Http\AsyncUpload\HandlesAsyncUpload;

function endpoint(string $uri, array $options = []): void
{
    Route::post($uri, function () use ($options) {
        $controller = new class
        {
            use HandlesAsyncUpload;

            public array $options = [];

            public function store(Request $request)
            {
                return $this->handleAsyncUpload($request, $this->options);
            }
        };

        $controller->options = $options;

        return $controller->store(request());
    })->middleware('web');
}

function chunk(array $overrides = []): array
{
    return array_merge([
        'chunk' => UploadedFile::fake()->createWithContent('chunk.bin', str_repeat('A', 64)),
        'chunk_index' => 0,
        'total_chunks' => 1,
        'chunk_size' => 64,
        'total_size' => 64,
        'session_id' => (string) Str::uuid(),
        'real_name' => 'file.bin',
        'mime' => 'application/octet-stream',
        'client_id' => 'client-1',
    ], $overrides);
}

beforeEach(function () {
    Storage::fake('local');

    endpoint('/_test/async-upload', ['directory' => 'uploads']);
});

it('cannot accept a chunk request with missing fields', function () {
    $this->postJson('/_test/async-upload', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'chunk',
            'chunk_index',
            'total_chunks',
            'chunk_size',
            'total_size',
            'session_id',
            'real_name',
            'mime',
            'client_id',
        ]);
});

it('cannot accept a chunk index above the total of chunks', function () {
    $this->postJson('/_test/async-upload', chunk(['chunk_index' => 5, 'total_chunks' => 2]))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['chunk_index']);
});

it('can stage an intermediate chunk as a part file', function () {
    Event::fake();

    $session = (string) Str::uuid();

    $this->postJson('/_test/async-upload', chunk([
        'session_id' => $session,
        'total_chunks' => 2,
        'total_size' => 128,
    ]))->assertNoContent();

    Storage::disk('local')->assertExists("async-uploads/{$session}/0.part");

    expect(Storage::disk('local')->size("async-uploads/{$session}/0.part"))->toBe(64);

    Event::assertDispatched(AsyncUploadStarted::class);
});

it('can accept chunks out of order without finalizing early', function () {
    $session = (string) Str::uuid();

    $base = ['session_id' => $session, 'total_chunks' => 3, 'total_size' => 192];

    foreach ([2, 0] as $index) {
        $this->postJson('/_test/async-upload', chunk($base + ['chunk_index' => $index]))->assertNoContent();
    }

    expect(Storage::disk('local')->files("async-uploads/{$session}"))->toHaveCount(2);

    Storage::disk('local')->assertExists("async-uploads/{$session}/2.part");
    Storage::disk('local')->assertMissing("async-uploads/{$session}/1.part");
});

it('can finalize once the part set is complete, in index order', function () {
    Event::fake();

    $session = (string) Str::uuid();

    $base = [
        'session_id' => $session,
        'total_chunks' => 2,
        'chunk_size' => 1024,
        'total_size' => 2048,
        'real_name' => 'merged.bin',
        'client_id' => 'client-7',
    ];

    // The last index is sent first: the assembled file must still read A then B.
    $this->postJson('/_test/async-upload', chunk($base + [
        'chunk' => UploadedFile::fake()->createWithContent('b.bin', str_repeat('B', 1024)),
        'chunk_index' => 1,
    ]))->assertNoContent();

    $response = $this->postJson('/_test/async-upload', chunk($base + [
        'chunk' => UploadedFile::fake()->createWithContent('a.bin', str_repeat('A', 1024)),
        'chunk_index' => 0,
    ]));

    $response->assertOk()
        ->assertJsonStructure(['id', 'path', 'real_name', 'size', 'mime', 'url'])
        ->assertJson(['id' => 'client-7', 'real_name' => 'merged.bin', 'size' => 2048]);

    expect(Storage::disk('local')->get($response->json('path')))
        ->toBe(str_repeat('A', 1024).str_repeat('B', 1024));

    Storage::disk('local')->assertMissing("async-uploads/{$session}");
    Storage::disk('local')->assertMissing("async-uploads/{$session}.sealed");

    Event::assertDispatched(AsyncUploadCompleted::class);
});

it('can sanitize the extension of the stored file', function () {
    $response = $this->postJson('/_test/async-upload', chunk(['real_name' => 'report..//..\\weird.PDF']));

    $response->assertOk();

    expect($response->json('path'))->toEndWith('.pdf')
        ->and($response->json('path'))->toStartWith('uploads/')
        ->and($response->json('real_name'))->toBe('report..//..\\weird.PDF');
});

it('cannot exceed the server side size ceiling', function () {
    Event::fake();

    endpoint('/_test/async-upload-capped', ['directory' => 'uploads', 'max_size' => 1]);

    $session = (string) Str::uuid();

    $this->postJson('/_test/async-upload-capped', chunk([
        'session_id' => $session,
        'total_size' => 50 * 1024 * 1024,
    ]))->assertStatus(422);

    Storage::disk('local')->assertMissing("async-uploads/{$session}");

    Event::assertDispatched(AsyncUploadFailed::class, fn (AsyncUploadFailed $event) => $event->reason === 'size');
});

it('cannot upload when the authorize callback denies it', function () {
    endpoint('/_test/async-upload-denied', ['directory' => 'uploads', 'authorize' => fn () => false]);

    $this->postJson('/_test/async-upload-denied', chunk())->assertStatus(403);
});

it('can apply extra validation rules against the assembled file', function () {
    Event::fake();

    endpoint('/_test/async-upload-strict', ['directory' => 'uploads', 'rules' => ['file' => ['mimes:jpg,png']]]);

    $this->postJson('/_test/async-upload-strict', chunk(['real_name' => 'plain.txt', 'mime' => 'text/plain']))
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['file']]);

    Event::assertDispatched(AsyncUploadFailed::class, fn (AsyncUploadFailed $event) => $event->reason === 'rules');
});

it('can delegate the persistence to the store callback', function () {
    endpoint('/_test/async-upload-store', [
        'store' => function (SplFileInfo $file): string {
            Storage::disk('local')->put('custom/place.bin', file_get_contents($file->getPathname()));

            return 'custom/place.bin';
        },
    ]);

    $this->postJson('/_test/async-upload-store', chunk())
        ->assertOk()
        ->assertJson(['path' => 'custom/place.bin']);

    Storage::disk('local')->assertExists('custom/place.bin');
});

it('cannot persist without a directory or a store callback', function () {
    $this->withoutExceptionHandling();

    endpoint('/_test/async-upload-nowhere');

    $this->postJson('/_test/async-upload-nowhere', chunk());
})->throws(AsyncUploadException::class);

it('can clean up orphan sessions older than the ttl', function () {
    Storage::disk('local')->put('async-uploads/old/0.part', 'partial');
    Storage::disk('local')->put('async-uploads/stale.sealed/0.part', 'partial');
    Storage::disk('local')->put('async-uploads/fresh/0.part', 'partial');

    foreach (['old', 'stale.sealed'] as $session) {
        touch(Storage::disk('local')->path("async-uploads/{$session}"), now()->subDay()->getTimestamp());
    }

    config()->set('ts-ui.components.upload.async.1.session_ttl', 60);

    __ts_get_component_configuration(Component::class, flush: true);

    Artisan::call('tallstackui:async-upload:clear');

    Storage::disk('local')->assertMissing('async-uploads/old/0.part');
    Storage::disk('local')->assertMissing('async-uploads/stale.sealed/0.part');
    Storage::disk('local')->assertExists('async-uploads/fresh/0.part');
});
