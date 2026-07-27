<?php

namespace TallStackUi\Http\AsyncUpload\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AsyncUploadFailed
{
    use Dispatchable;

    /** @param array<string, array<int, string>> $errors */
    public function __construct(
        public readonly string $reason,
        public readonly string $sessionId,
        public readonly string $clientId,
        public readonly string $realName,
        public readonly array $errors = [],
    ) {
        //
    }
}
