<?php

namespace TallStackUi\Http\AsyncUpload;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait Uploader
{
    /** @param array<string, mixed> $options */
    public function upload(Request $request, array $options = []): JsonResponse|Response
    {
        return (new AsyncUploadHandler($options))->handle($request);
    }
}
