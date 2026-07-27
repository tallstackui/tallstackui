<?php

namespace TallStackUi\Http\AsyncUpload\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AsyncUploadStarted
{
    use Dispatchable;

    public function __construct(
        public readonly string $uuid,
        public readonly string $realName,
        public readonly string $mime,
        public readonly int $totalSize,
        public readonly int $totalChunks,
    ) {
        //
    }
}
