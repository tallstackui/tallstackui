<?php

namespace TallStackUi\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

trait MultipleUpload
{
    use WithFileUploads;

    public function _finishUpload($name, $tmpPath, $isMultiple): void
    {
        $this->cleanupOldUploads();

        if ($isMultiple) {
            $file = collect($tmpPath)->map(function ($i) {
                return TemporaryUploadedFile::createFromLivewire($i);
            })->toArray();
            $this->dispatch('upload:finished', name: $name, tmpFilenames: collect($file)->map->getFilename()->toArray())->self();
        } else {
            $file = TemporaryUploadedFile::createFromLivewire($tmpPath[0]);
            $this->dispatch('upload:finished', name: $name, tmpFilenames: [$file->getFilename()])->self();
        }

        if (is_array($value = $this->getPropertyValue($name))) {
            $file = Arr::flatten(array_merge($value, [$file]));
            $file = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();
        }

        app('livewire')->updateProperty($this, $name, $file);
    }
}
