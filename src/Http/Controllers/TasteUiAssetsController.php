<?php

namespace TasteUi\Http\Controllers;

use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TasteUiAssetsController
{
    public function scripts(): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/tasteui.js', 'text/javascript');
    }

    public function styles(): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/tasteui.css', 'text/css');
    }
}
