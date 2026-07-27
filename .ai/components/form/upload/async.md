# TallStackUI: Upload Async

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A chunked, asynchronous file upload component. The browser slices each file and posts the pieces straight to a controller of your own, bypassing Livewire's upload pipeline entirely, so a file no longer has to fit inside PHP's request limits. Files around 1 GB are the target.

It shares nothing with `<x-upload />` beyond the namespace. Unlike that one, it works outside Livewire too, binding either to a `wire:model` or to a plain form through `name`.

## Basic Usage

```blade
<x-upload.async wire:model="document" :route="route('uploads.store')" label="Document" />
```

```blade
<x-upload.async wire:model="gallery"
                :route="route('uploads.gallery')"
                label="Gallery"
                accept="image/*"
                multiple
                :limit="6"
                :columns="4"
                :max-size="512" />
```

```blade
<x-upload.async wire:model="files" :route="route('uploads.store')" multiple manual />
```

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    <x-upload.async name="attachments" :route="route('uploads.store')" multiple />
    <x-button type="submit" text="Save" />
</form>
```

The `route` accepts a named route or a plain URL. Either `wire:model` or `name` is required.

## Attributes

| Attribute   | Type                             | Default            | Description                                                             |
|-------------|----------------------------------|--------------------|-------------------------------------------------------------------------|
| route       | string **required**              | —                  | Named route or URL the chunks are posted to                             |
| method      | string                           | 'POST'             | HTTP method used for every chunk                                        |
| label       | string\|ComponentSlot\|null      | null               | Label text displayed above the drop area                                |
| hint        | string\|ComponentSlot\|null      | null               | Hint text displayed below the drop area                                 |
| title       | string\|null                     | translation        | Placeholder title inside the drop area                                  |
| description | string\|null                     | translation        | Placeholder subtitle inside the drop area                               |
| tip         | string\|ComponentSlot\|null      | null               | Extra line under the description, hidden once a file is picked          |
| multiple    | bool                             | false              | Allows multiple file selection                                          |
| manual      | bool                             | false              | Stages the files and waits for the Send button                          |
| disabled    | bool                             | false              | Blocks drop, click and keyboard                                         |
| limit       | int\|null                        | null               | Maximum number of files, only meaningful with `multiple`                |
| max-size    | int\|null                        | config             | Maximum megabytes per file, also enforced by the handler                |
| accept      | string\|null                     | config             | Mime/extension filter (e.g. `image/*,.pdf`)                             |
| files       | array\|Collection\|null          | null               | Pre-existing files, same shape as the bound value                       |
| height      | string\|null                     | 'min-h-48'         | Tailwind min-height of the drop area                                    |
| columns     | int\|null                        | 6                  | Maximum grid columns, clamped to 1..6                                   |
| chunk-size  | int\|null                        | config             | Bytes per chunk                                                         |
| concurrency | int\|null                        | config             | Chunks uploaded in parallel, per component                              |
| retries     | int\|null                        | config             | Attempts per chunk on transient failures                                |
| headers     | array\|null                      | null               | Extra HTTP headers. The CSRF token is injected automatically            |
| footer      | ComponentSlot\|null              | null               | Replaces the built-in Send/Clear footer of manual mode                  |
| error       | string\|bool\|null               | translation        | Component-level fallback error message                                  |

## Slots

| Slot   | Description                                                                                     |
|--------|-------------------------------------------------------------------------------------------------|
| footer | Replaces the Send and Clear buttons rendered by manual mode. Ignored when `manual` is not set.  |

## Bound Value

Both `wire:model` and `name` receive the same shape, an array of finished uploads. With `multiple` off, a single object or `null`.

```php
[
    [
        'id' => '9f8c2b1e-...',
        'path' => 'posts/attachments/abc-def.jpg',
        'real_name' => 'photo.jpg',
        'size' => 1234567,
        'mime' => 'image/jpeg',
        'url' => '/storage/posts/attachments/abc-def.jpg',
    ],
]
```

`url` is `null` when the destination disk has no public URL. With `name`, the same data is rendered as hidden inputs so a plain form submit carries it.

Following `wire:model` semantics, the array is synced on the next round trip. Use `wire:model.live` to push it immediately.

## Backend

The endpoint is yours. The `Uploader` trait handles the chunk protocol:

```php
use Illuminate\Http\Request;
use TallStackUi\Http\AsyncUpload\Uploader;

class UploadController
{
    use Uploader;

    public function store(Request $request)
    {
        return $this->upload($request, [
            'disk' => 'public',
            'directory' => 'posts/attachments',
            'rules' => ['file' => ['mimes:jpg,png,pdf']],
        ]);
    }
}
```

The method is called once per chunk. Intermediate chunks answer `204`; the last one assembles the file, stores it and answers `200` with the payload above.

> Naming the `directory` is required. Without it, and without a `store` callback, the handler throws instead of guessing a destination.

### Options

| Option    | Type     | Default          | Description                                                                     |
|-----------|----------|------------------|---------------------------------------------------------------------------------|
| disk      | string   | config           | Destination disk. Any driver, including S3                                      |
| directory | string   | **required**     | Destination directory on that disk                                              |
| rules     | array    | null             | Laravel rules applied to the assembled file, under the `file` key               |
| store     | callable | null             | Receives `(SplFileInfo $file, AsyncUploadRequest $request)` and returns the final path, skipping the built-in move |
| authorize | callable | null             | Receives `(AsyncUploadRequest $request)`; returning `false` aborts with `403`    |
| max_size  | int      | config           | Per-endpoint override of the megabyte ceiling                                   |
| tmp_disk  | string   | config           | Staging disk. Must use the local driver                                         |

### Taking over persistence

The callback receives the assembled file and returns its final path. This is where a library like MediaLibrary plugs in:

```php
return $this->upload($request, [
    'disk' => 'public',
    'store' => fn (SplFileInfo $file, AsyncUploadRequest $request): string => $post
        ->addMedia($file)
        ->usingFileName($request->input('real_name'))
        ->toMediaCollection('attachments')
        ->getPathRelativeToRoot(),
]);
```

It runs once per file, not once per chunk, after the pieces are joined and validated. Whatever path it returns must exist on the disk named in `disk`, since the handler reads the size, mime and URL back from there.

### Guards

`max-size` on the component is feedback for the user; a request built by hand would ignore it. The handler re-checks the declared size on every chunk and compares the assembled bytes to it at the end, so neither can be lied about. `rules` run against the real bytes, not the mime the browser claimed.

Route middleware is yours. The `authorize` option sits on top of it, for rules middleware cannot express. It receives the validated `AsyncUploadRequest` and runs on every chunk, before anything is written:

```php
'authorize' => fn (AsyncUploadRequest $request): bool => $request->user()->can('upload', $post),
```

### Cleaning up

An upload that starts and never finishes leaves its pieces staged. The command discards whatever has been idle longer than the `keep` setting:

```php
// routes/console.php
Schedule::command('tallstackui:async-upload:clear')->daily();
```

Nothing else collects them, so without this the staging directory grows forever. Finalized files are never touched: telling an orphan from a saved file there would need your database.

## Events

### Alpine.js

Dispatched on the component root. All payloads arrive on `event.detail`.

| Event    | When                                                     | Detail                                       |
|----------|----------------------------------------------------------|----------------------------------------------|
| added    | File passed the client-side checks and entered the queue | `{ file }`                                   |
| rejected | File blocked by `accept`, `max-size` or `limit`          | `{ file, reason: 'mime'\|'size'\|'limit' }`  |
| start    | Chunk loop began for a file                              | `{ file }`                                   |
| progress | Per-file progress update                                 | `{ file, progress }` (0..100)                |
| success  | Backend accepted the file                                | `{ file, response }`                         |
| error    | Definitive failure, retries exhausted                    | `{ file, error, status }`                    |
| removed  | File removed from the grid                               | `{ file }`                                   |
| complete | Whole queue finished, whatever the outcome               | `{ files }`                                  |

```blade
<x-upload.async wire:model="files"
                :route="route('uploads.store')"
                x-on:success="console.log($event.detail.file)"
                x-on:complete="console.log($event.detail.files)" />
```

### Laravel

Three events for side effects: queueing a thumbnail, scanning, auditing.

| Event                | When                                                    | Payload                                                       |
|----------------------|---------------------------------------------------------|---------------------------------------------------------------|
| AsyncUploadStarted   | First chunk of a file landed                            | `uuid`, `realName`, `mime`, `totalSize`, `totalChunks`        |
| AsyncUploadCompleted | File assembled, validated and stored                    | `response`, `disk`, `uuid`                                    |
| AsyncUploadFailed    | A guard, the rules or the integrity check rejected it   | `reason`, `uuid`, `realName`, `errors`                        |

`$reason` is one of `unauthorized`, `size`, `integrity` or `rules`. There is deliberately no per-chunk event: a 500 MB file would fire hundreds.

> `AsyncUploadCompleted` is not the place to write a database row. A finished upload is not a submitted form, and the user may still close the tab. That write belongs where the form is handled, reading the array the component synced out.

## Configuration

Global defaults live under `components.upload.async` in the published config.

| Setting       | Default          | Description                                                            |
|---------------|------------------|------------------------------------------------------------------------|
| chunk_size    | 2 MB             | Bytes per chunk. Must stay below the PHP `upload_max_filesize`         |
| concurrency   | 3                | Chunks uploaded in parallel, per component                             |
| retries       | 3                | Attempts per chunk on 5xx, 408, 429 and network errors                 |
| retry_delay   | 1000             | Milliseconds between retries, with exponential backoff                 |
| max_size      | null             | Maximum megabytes per file. null = unlimited                           |
| accept        | null             | Default mime/extension filter. null = any                              |
| tmp_disk      | 'local'          | Disk used to stage the chunks. Must use the local driver               |
| tmp_directory | 'async-uploads'  | Directory, inside `tmp_disk`, used to stage the chunks                 |
| disk          | 'local'          | Destination disk of the finalized files                                |
| keep          | 6 hours          | Seconds an unfinished upload is kept before the clear command drops it |

The `aria-label` of the remove button and of the lightbox close button are not
translated: they carry a fixed English string.

Raising `chunk_size` requires raising the PHP limits with it, otherwise every chunk is rejected before reaching Laravel. The staging disk must be local because joining the pieces needs real filesystem paths; the destination has no such restriction.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('upload.async')
    ->block('dropzone.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                  | Purpose                                                          |
|-----------------------------|------------------------------------------------------------------|
| wrapper                     | Outermost container of the component                             |
| dropzone.base               | Drop area frame: border, radius, padding and scrollbar clipping  |
| dropzone.dragging           | Drop area while a file is being dragged over it                  |
| dropzone.disabled           | Drop area while disabled                                         |
| dropzone.input              | Hidden file input overlay                                        |
| dropzone.placeholder        | Placeholder container, shared by both sizes                      |
| dropzone.placeholder-full   | Placeholder layout while no file has been picked                 |
| dropzone.placeholder-compact| Placeholder layout once files are in the grid                    |
| dropzone.icon               | Placeholder icon colour                                          |
| dropzone.icon-full          | Placeholder icon size while empty                                |
| dropzone.icon-compact       | Placeholder icon size once filled                                |
| dropzone.icon-bouncing      | Placeholder icon animation while dragging                        |
| dropzone.title              | Placeholder title colour                                         |
| dropzone.title-full         | Placeholder title size while empty                               |
| dropzone.title-compact      | Placeholder title size once filled                               |
| dropzone.description        | Placeholder description colour                                   |
| dropzone.description-full   | Placeholder description size while empty                         |
| dropzone.description-compact| Placeholder description size once filled                         |
| dropzone.tip                | Placeholder tip text                                             |
| grid.wrapper                | File grid scroll container                                       |
| grid.multiple               | Grid width in multiple mode                                      |
| grid.single                 | Grid width in single mode                                        |
| grid.cols.1 … grid.cols.6   | Responsive column counts, picked by the `columns` attribute      |
| tile.wrapper                | File tile container                                              |
| tile.image                  | Image thumbnail inside the tile                                  |
| tile.document               | Non-image tile container                                         |
| tile.document-icon          | Non-image tile icon                                              |
| tile.extension              | Uppercase extension pill of a non-image tile                     |
| tile.name-overlay           | File name gradient at the top of the tile                        |
| tile.size-overlay           | File size gradient at the bottom of the tile                     |
| tile.remove                 | Remove button of the tile                                        |
| tile.remove-icon            | Remove button icon                                               |
| tile.progress               | Per-file progress bar                                            |
| tile.success-mark           | Badge shown once the file is stored                              |
| tile.error-ring             | Ring drawn around a rejected or failed tile                      |
| tile.error-badge            | Badge shown on a rejected or failed tile                         |
| tile.error-msg              | Error message rendered over the tile                             |
| lightbox.backdrop           | Fullscreen image preview backdrop                                |
| lightbox.wrapper            | Preview image container                                          |
| lightbox.image              | Preview image                                                    |
| lightbox.caption            | File name overlaid on the preview                                |
| lightbox.close              | Preview close button                                             |
| lightbox.close-icon         | Preview close button icon                                        |
| footer.wrapper              | Manual mode footer container                                     |
| footer.summary              | Manual mode file count and total size                            |
| footer.actions              | Manual mode button group                                         |
| error.wrapper               | Error message container                                          |
| error.message               | Error message text                                               |
