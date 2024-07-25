<?php

namespace TallStackUi\Http\Controllers;

use Exception;
use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use TallStackUi\Foundation\Support\Blade\BladeDirectives;

class TallStackUiAssetsController
{
    /** @throws Exception */
    public function script(?string $file = null): Response|BinaryFileResponse
    {
        if (! file_exists($path = __DIR__.'/../../../dist/'.$file)) {
            $files = BladeDirectives::built('js');

            abort(new Response('File not found: '.$file.'. Available files: '.implode(', ', $files), 404));
        }

        return Utils::pretendResponseIsFile($path, 'text/javascript');
    }

    /** @throws Exception */
    public function style(?string $file = null): Response|BinaryFileResponse
    {
        if (! file_exists($path = __DIR__.'/../../../dist/'.$file)) {
            $files = BladeDirectives::built('js');

            abort(new Response('File not found: '.$file.'. Available files: '.implode(', ', $files), 404));
        }

        return Utils::pretendResponseIsFile($path, 'text/css');
    }
}
