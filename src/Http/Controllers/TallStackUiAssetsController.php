<?php

namespace TallStackUi\Http\Controllers;

use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TallStackUiAssetsController
{
    public function script(?string $file = null): Response|BinaryFileResponse
    {
        $file ??= tallstackui_load_vite_manitefest('js/tallstackui.js');

        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/'.$file, 'text/javascript');
    }

    public function style(?string $file = null): Response|BinaryFileResponse
    {
        $file ??= tallstackui_load_vite_manitefest('src/resources/css/tallstackui.css');

        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/'.$file, 'text/css');
    }
}
