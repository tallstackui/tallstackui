<?php

namespace TallStackUi\Http\Controllers;

use Exception;
use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TallStackUiAssetsController
{
    /**
     * The path to the dist directory.
     */
    protected const DIST_PATH = __DIR__.'/../../../dist';

    /** @throws Exception */
    public function script(?string $file = null): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(self::DIST_PATH.'/'.$file, 'text/javascript');
    }

    /** @throws Exception */
    public function style(?string $file = null): Response|BinaryFileResponse
    {
        return Utils::pretendResponseIsFile(self::DIST_PATH.'/'.$file, 'text/css');
    }
}
