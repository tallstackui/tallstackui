<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

// The main objective of this class is to adapt the content received through
// the upload component mapped to two different formats: upload files or existing
// files. At the end, with this class we avoid possible errors in Blade that would
// occur without the correct formatting of the names used in Blade.
class UploadComponentFileAdapter
{
    public function __construct(
        private readonly bool $static,
        private readonly TemporaryUploadedFile|array $files,
        private ?Collection $collection = null,
        private ?bool $size = false,
    ) {
        $this->collection = collect(Arr::wrap($this->files));

        // A simple way to avoid exceptions when PHP intl
        // and Laravel Number classes are not available.
        $this->size = extension_loaded('intl') && class_exists(Number::class);
    }

    /** @throws Exception */
    public function __invoke(): array
    {
        return ($this->static ? $this->static() : $this->upload())->toArray();
    }

    private function image(?string $extension): bool
    {
        if (! $extension) {
            return false;
        }

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    }

    /** @throws Exception */
    private function static(): Collection
    {
        $required = ['name', 'extension', 'size', 'url'];

        // To avoid exceptions in Blade we ensure that the files
        // sent have a specific format, containing all these keys.
        $this->collection->each(function (array $file) use ($required) {
            foreach ($required as $key) {
                if (! array_key_exists($key, $file) || blank($file[$key])) {
                    throw new Exception("The [upload] as [static] requires the [{$key}] key to be present in all items.");
                }
            }
        });

        return $this->collection->map(fn (array $file) => [
            'temporary_name' => $file['name'],
            'real_name' => $file['name'],
            'extension' => $file['extension'],
            'size' => $this->size ? Number::fileSize($file['size']) : null,
            'path' => $file['path'],
            'is_image' => $image = $this->image($file['extension']),
            'url' => ! $image ?: $file['url'],
        ]);
    }

    private function upload(): Collection
    {
        return $this->collection->map(fn (TemporaryUploadedFile $file) => [
            'temporary_name' => $file->getFilename(),
            'real_name' => $file->getClientOriginalName(),
            'extension' => $extension = $file->extension(),
            'size' => $this->size ? Number::fileSize($file->getSize()) : null,
            'path' => $file->getPathname(),
            'is_image' => $image = $this->image($extension),
            'url' => ! $image ?: $file->temporaryUrl(),
        ]);
    }
}
