<?php

namespace TallStackUi\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait WithChunkUploads
{
    public function uploadChunk(string $property, string $identifier, string $chunkData, int $chunkIndex, int $totalChunks): void
    {
        $disk = Storage::disk(FileUploadConfiguration::disk());
        $path = 'tallstackui-chunks/' . $identifier;

        if (!$disk->exists($path)) {
            $disk->makeDirectory($path);
        }

        $chunk = base64_decode($chunkData);
        $disk->put($path . '/' . $chunkIndex . '.part', $chunk);
    }

    public function finishChunkUpload(string $property, string $identifier, string $filename, int $totalChunks, bool $isMultiple = false): void
    {
        $disk = Storage::disk(FileUploadConfiguration::disk());
        $path = 'tallstackui-chunks/' . $identifier;

        $tempPath = tempnam(sys_get_temp_dir(), 'tsui_upload_');
        $handle = fopen($tempPath, 'wb');

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $path . '/' . $i . '.part';
            if ($disk->exists($chunkPath)) {
                fwrite($handle, $disk->get($chunkPath));
            }
        }

        fclose($handle);

        $file = new UploadedFile($tempPath, $filename, null, null, true);

        // This stores the file in Livewire's temporary directory and returns the path
        $storePath = $file->store(FileUploadConfiguration::path(), FileUploadConfiguration::disk());

        // We need to create the metadata file for Livewire to be able to
        // retrieve the original file name and other information.
        $disk->put($storePath . '.json', json_encode([
            'name' => $filename,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
        ]));

        // Create TemporaryUploadedFile instance
        // createFromLivewire expects the file name, not the full path,
        // because it prepends the directory using FileUploadConfiguration::path()
        $temporaryFile = TemporaryUploadedFile::createFromLivewire(basename($storePath));

        // Cleanup chunks and temp file
        $disk->deleteDirectory($path);
        @unlink($tempPath);

        // Assign to property
        if ($isMultiple) {
            $current = $this->getPropertyValue($property);

            if (!is_array($current)) {
                $current = $current ? [$current] : [];
            }

            $current[] = $temporaryFile;
            $this->fill([$property => $current]);
        } else {
            $this->fill([$property => $temporaryFile]);
        }

        // Trigger validation if needed
        // $this->validateOnly($property); // Optional, might be handled by user
    }
}
