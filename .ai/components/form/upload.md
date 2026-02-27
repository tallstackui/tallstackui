# TallStackUI: Upload

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A file upload component with drag-and-drop support, upload progress tracking, image previews, file deletion, and a static mode for displaying previously uploaded files. Integrates with Livewire's file upload system and renders uploaded files in a floating panel.

## Basic Usage

```blade
<x-upload wire:model="photos" label="Photos" tip="JPG, PNG up to 5MB" multiple delete />
```

```blade
<x-upload wire:model="document" label="Document" :preview="false" />
```

```blade
<x-upload wire:model="files" label="Attachments" multiple delete close-after-upload>
    <x-slot:footer when-uploaded>
        <x-button wire:click="save" text="Save Files" />
    </x-slot:footer>
</x-upload>
```

```blade
<x-upload wire:model="avatar" label="Existing Files" static delete />
```

## Attributes

| Attribute          | Type                        | Default        | Description                                                                  |
|--------------------|-----------------------------|----------------|------------------------------------------------------------------------------|
| label              | string\|ComponentSlot\|null | null           | Label text displayed above the upload input                                  |
| hint               | string\|ComponentSlot\|null | null           | Hint text displayed below the upload input                                   |
| tip                | string\|ComponentSlot\|null | null           | Tip text displayed inside the drag-and-drop area                             |
| multiple           | bool                        | false          | Allows multiple file selection                                               |
| preview            | bool                        | true           | Enables image preview on click (fullscreen lightbox)                         |
| delete             | bool                        | false          | Shows a delete button next to each uploaded file                             |
| static             | bool                        | false          | Static mode for displaying previously uploaded files (no drag-and-drop area) |
| placeholder        | string\|null                | null           | Custom placeholder text for the input (defaults to translation)              |
| delete-method      | string                      | 'deleteUpload' | Name of the Livewire method called when deleting a file                      |
| error              | string\|bool\|null          | null           | Custom error message for upload failures (defaults to translation)           |
| footer             | ComponentSlot\|null         | null           | Footer slot content displayed at the bottom of the floating panel            |
| overflow           | bool\|null                  | null           | Controls body overflow when the upload panel is open                         |
| close-after-upload | bool\|null                  | null           | Automatically closes the floating panel after files are uploaded             |

## Slots

| Slot   | Description                                                                                                                          |
|--------|--------------------------------------------------------------------------------------------------------------------------------------|
| tip    | Custom content for the upload area tip. Can be a string or a full slot with markup.                                                  |
| footer | Footer content rendered at the bottom of the floating panel. Supports `when-uploaded` attribute to only show when files are present. |

## Livewire Integration

### Delete Method

When `delete` is enabled, clicking the delete icon calls a `deleteUpload` method on your Livewire component. The method receives an array with file metadata:

```php
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;

public function deleteUpload(array $content): void
{
    /*
     $content contains:
     [
         'temporary_name',
         'real_name',
         'extension',
         'size',
         'path',
         'url',
     ]
     */

    if (! $this->photo) {
        return;
    }

    $files = Arr::wrap($this->photo);

    /** @var UploadedFile $file */
    $file = collect($files)
        ->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])
        ->first();

    rescue(fn () => $file->delete(), report: false);

    $collect = collect($files)
        ->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

    $this->photo = is_array($this->photo) ? $collect->toArray() : $collect->first();
}
```

To use a different method name: `<x-upload delete delete-method="removeFile" />`

### Multiple File Uploads (Batch Merging)

When uploading multiple files in batches, new selections replace previous ones by default. Use Livewire lifecycle hooks to merge batches:

```php
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;

class MyComponent extends Component
{
    use WithFileUploads;

    public $photos = [];
    public $backup = [];

    public function updatingPhotos(): void
    {
        $this->backup = $this->photos;
    }

    public function updatedPhotos(): void
    {
        if (!$this->photos) {
            return;
        }

        $file = Arr::flatten(array_merge($this->backup, [$this->photos]));

        $this->photos = collect($file)
            ->unique(fn (UploadedFile $item) => $item->getClientOriginalName())
            ->toArray();
    }
}
```

If using a different property name (e.g., `$files` instead of `$photos`), rename the lifecycle hooks accordingly: `updatingFiles()`, `updatedFiles()`.

### Static Mode (Displaying Existing Files)

Static mode displays previously uploaded files without the drag-and-drop area:

```php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\SplFileInfo;

public function mount(): void
{
    $this->photos = collect(File::allFiles(public_path('storage/images')))
        ->map(fn (SplFileInfo $file) => [
            'name' => $file->getFilename(),
            'extension' => $file->getExtension(),
            'size' => $file->getSize(),
            'path' => $file->getPath(),
            'url' => Storage::url('images/'.$file->getFilename()),
        ])->toArray();
}
```

Blade usage:

```blade
<x-upload wire:model="photos" static />
<x-upload wire:model="photos" static delete />
<x-upload wire:model="photos" :placeholder="count($photos) . ' images'" static delete />
```

Static mode delete method differs (files are arrays, not UploadedFile):

```php
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

public function deleteUpload(array $content): void
{
    if (empty($this->photos)) {
        return;
    }

    File::delete($content['path']);

    $this->photos = collect(Arr::wrap($this->photos))
        ->filter(fn (array $item) => $item['name'] !== $content['real_name'])
        ->toArray();
}
```

### Alpine.js Events

```blade
<!-- Fires when files are uploaded -->
<x-upload x-on:upload="console.log($event.detail.files)" />

<!-- Fires when a file is deleted -->
<x-upload delete x-on:remove="console.log($event.detail.file)" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('upload')
    ->block('placeholder.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                                  |
|--------------------------|----------------------------------------------------------|
| icon                     | Icon styles for the upload trigger button                |
| placeholder.input        | Hidden file input overlay styles                         |
| placeholder.wrapper      | Drag-and-drop area container (border, background, shape) |
| placeholder.title        | Upload area title text styles                            |
| placeholder.tip          | Upload area tip text styles                              |
| placeholder.icon.class   | Upload area icon styles                                  |
| placeholder.icon.wrapper | Upload area icon container                               |
| floating.default         | Default floating panel wrapper styles                    |
| floating.class           | Floating panel additional styles                         |
| upload.wrapper           | Progress bar outer wrapper                               |
| upload.progress          | Progress bar fill styles                                 |
| item.wrapper             | File list scrollable container                           |
| item.ul                  | File list divider styles                                 |
| item.li                  | File list item flex layout                               |
| item.title               | File name text styles                                    |
| item.size                | File size text styles                                    |
| item.image               | Image thumbnail styles (rounded, sized)                  |
| item.document            | Document icon styles for non-image files                 |
| item.delete              | Delete icon styles                                       |
| preview.backdrop         | Fullscreen image preview backdrop                        |
| preview.wrapper          | Preview image container                                  |
| preview.image            | Preview image styles                                     |
| preview.button.base      | Preview close button positioning                         |
| preview.button.icon      | Preview close button icon styles                         |
| error.wrapper            | Error message container                                  |
| error.message            | Error message text styles                                |
| static.empty.wrapper     | Empty state wrapper for static mode                      |
| static.empty.icon        | Empty state icon                                         |
| static.empty.title       | Empty state title text                                   |
| static.empty.description | Empty state description text                             |
| invalid                  | Validation error message text below the input            |
