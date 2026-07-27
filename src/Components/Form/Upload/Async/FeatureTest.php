<?php

use Illuminate\Support\Facades\Route;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    Route::post('/uploads', fn () => 'ok')->name('uploads.store');
});

it('cannot render without a binding', function () {
    $this->expectException(ViewException::class);

    expect('<x-upload.async route="uploads.store" />')->render();
});

it('can render with a route name', function () {
    expect('<x-upload.async name="files" route="uploads.store" />')
        ->render()
        ->toContain('tallstackui_formUploadAsync')
        ->toContain(str_replace('/', '\/', route('uploads.store')));
});

it('can render with a plain url', function () {
    expect('<x-upload.async name="files" route="/api/uploads" />')
        ->render()
        ->toContain('\/api\/uploads');
});

it('can render existing files', function () {
    $files = [
        [
            'id' => 'a',
            'path' => 'posts/a.jpg',
            'real_name' => 'foo.jpg',
            'size' => 1024,
            'mime' => 'image/jpeg',
            'url' => '/storage/posts/a.jpg',
        ],
    ];

    expect('<x-upload.async name="files" route="/api/uploads" :files="$files" />')
        ->render(['files' => $files])
        ->toContain('foo.jpg');
});

it('can render with label and hint', function () {
    expect('<x-upload.async name="files" route="/api/uploads" label="Pictures" hint="Max 10 MB" />')
        ->render()
        ->toContain('Pictures')
        ->toContain('Max 10 MB');
});

it('can render clamping the columns', function () {
    expect('<x-upload.async name="files" route="/api/uploads" :columns="3" />')
        ->render()
        ->toContain('sm:grid-cols-3');

    expect('<x-upload.async name="files" route="/api/uploads" :columns="999" />')
        ->render()
        ->toContain('xl:grid-cols-6');
});

it('can render the accept attribute', function () {
    expect('<x-upload.async name="files" route="/api/uploads" accept="image/*,.pdf" />')
        ->render()
        ->toContain('accept="image/*,.pdf"');
});

it('can render the manual footer', function () {
    expect('<x-upload.async name="files" route="/api/uploads" manual multiple />')
        ->render()
        ->toContain('send()')
        ->toContain('clear()');
});

it('cannot render the manual footer when a footer slot is given', function () {
    $component = <<<'BLADE'
    <x-upload.async name="files" route="/api/uploads" manual multiple>
        <x-slot:footer>
            <span data-marker="custom-footer">Foo</span>
        </x-slot:footer>
    </x-upload.async>
    BLADE;

    expect($component)
        ->render()
        ->toContain('custom-footer')
        ->not->toContain('sendable()');
});

it('can render the hidden inputs when using the name attribute', function () {
    expect('<x-upload.async name="files" route="/api/uploads" multiple />')
        ->render()
        ->toContain('files[${index}][path]');
});

it('can render forwarding alpine listeners', function () {
    expect('<x-upload.async name="files" route="/api/uploads" x-on:success="foo()" />')
        ->render()
        ->toContain('x-on:success="foo()"');
});

it('can render applying the global configuration', function () {
    expect('<x-upload.async name="files" route="/api/uploads" />')
        ->render()
        ->toContain('chunk_size\\u0022:2097152')
        ->toContain('concurrency\\u0022:3');
});

it('can render overriding the global configuration', function () {
    expect('<x-upload.async name="files" route="/api/uploads" :chunk-size="1024" :retries="9" />')
        ->render()
        ->toContain('chunk_size\\u0022:1024')
        ->toContain('retries\\u0022:9');
});
