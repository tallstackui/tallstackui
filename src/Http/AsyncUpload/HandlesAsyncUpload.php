<?php

namespace TallStackUi\Http\AsyncUpload;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait HandlesAsyncUpload
{
    /** @param array<string, mixed> $options */
    public function handleAsyncUpload(Request $request, array $options = []): JsonResponse|Response
    {
        return (new AsyncUploadHandler($options))->handle($request);
    }
}
