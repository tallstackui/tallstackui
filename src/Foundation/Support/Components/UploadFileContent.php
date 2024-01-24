<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadFileContent
{
    public function __construct(
        private readonly bool $static,
        private readonly array $files,
        private ?Collection $collection = null,
    ) {
        $this->collection = collect($this->files);
    }

    /** @throws Exception */
    public function __invoke(): array
    {
        return ($this->static ? $this->static() : $this->upload())->toArray();
    }

    private function image(string $extension): bool
    {
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
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

        return $this->collection->map(function (array $file) {
            return [
                ...$file,
                'temporary_name' => $file['name'],
                'real_name' => $file['name'],
                'is_image' => $this->image($file['extension']),
                'size' => Number::fileSize($file['size']),
            ];
        });
    }

    private function upload(): Collection
    {
        return $this->collection->map(function (TemporaryUploadedFile $file) {
            return [
                'temporary_name' => $file->getClientOriginalName(),
                'real_name' => $file->getFilename(),
                'extension' => $file->extension(),
                'size' => Number::fileSize($file->getSize()),
                'is_image' => $this->image($file->extension()),
                'url' => $file->temporaryUrl(),
            ];
        });
    }
}
