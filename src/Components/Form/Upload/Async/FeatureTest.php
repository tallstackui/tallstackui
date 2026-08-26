<?php

use Illuminate\Support\Facades\Route;
use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Upload\Async\Component;
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

it('cannot render the editor by default', function () {
    expect('<x-upload.async name="files" route="/api/uploads" />')
        ->render()
        ->not->toContain('tallstackui_upload_editor');
});

it('can render the editor with crop and rotate', function () {
    expect('<x-upload.async name="files" route="/api/uploads" editor />')
        ->render()
        ->toContain('tallstackui_upload_editor_crop')
        ->toContain('tallstackui_upload_editor_rotate_left')
        ->toContain('crop\\u0022:true')
        ->toContain('rotate\\u0022:true');
});

it('can render the editor with crop only', function () {
    expect('<x-upload.async name="files" route="/api/uploads" editor="crop" />')
        ->render()
        ->toContain('tallstackui_upload_editor_crop')
        ->not->toContain('tallstackui_upload_editor_rotate_left');
});

it('can render the editor with rotate only', function () {
    expect('<x-upload.async name="files" route="/api/uploads" editor="rotate" />')
        ->render()
        ->toContain('tallstackui_upload_editor_rotate_left')
        ->not->toContain('tallstackui_upload_editor_crop');
});

it('can render the editor with a locked aspect', function () {
    expect('<x-upload.async name="files" route="/api/uploads" editor aspect="16:9" />')
        ->render()
        ->toContain('aspect\\u0022:1.777');
});

it('cannot render the editor with an invalid mode', function () {
    $this->expectException(ViewException::class);

    expect('<x-upload.async name="files" route="/api/uploads" editor="flip" />')->render();
});

it('cannot render the editor with an invalid aspect', function () {
    $this->expectException(ViewException::class);

    expect('<x-upload.async name="files" route="/api/uploads" editor aspect="wide" />')->render();
});

// The [upload.async] key carries a dot, so the whole list is replaced
// instead of going through the dotted config()->set() path.
$configure = function (array $settings): void {
    $components = config('ts-ui.components');
    $components['upload.async'][1] = array_merge($components['upload.async'][1], $settings);

    config()->set('ts-ui.components', $components);

    __ts_get_component_configuration(Component::class, flush: true);
};

it('can render the editor through the global configuration', function () use ($configure) {
    $configure(['editor' => 'crop', 'aspect' => '1:1']);

    expect('<x-upload.async name="files" route="/api/uploads" />')
        ->render()
        ->toContain('tallstackui_upload_editor_crop')
        ->toContain('aspect\\u0022:1');

    $configure(['editor' => false, 'aspect' => null]);
});

it('can render disabling the editor inline over the global configuration', function () use ($configure) {
    $configure(['editor' => true]);

    expect('<x-upload.async name="files" route="/api/uploads" :editor="false" />')
        ->render()
        ->not->toContain('tallstackui_upload_editor');

    $configure(['editor' => false]);
});
