<?php

namespace TallStackUi\Http\AsyncUpload;

use Illuminate\Contracts\Support\Arrayable;

final class AsyncUploadResponse implements Arrayable
{
    public function __construct(
        public readonly string $id,
        public readonly string $path,
        public readonly string $realName,
        public readonly int $size,
        public readonly string $mime,
        public readonly ?string $url = null,
    ) {
        //
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'real_name' => $this->realName,
            'size' => $this->size,
            'mime' => $this->mime,
            'url' => $this->url,
        ];
    }
}
