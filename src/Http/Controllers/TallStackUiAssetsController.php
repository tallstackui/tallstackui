<?php

namespace TallStackUi\Http\Controllers;

use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TallStackUiAssetsController
{
    public function scripts(): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/tallstackui.js', 'text/javascript');
    }

    public function styles(): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/tallstackui.css', 'text/css');
    }
}
