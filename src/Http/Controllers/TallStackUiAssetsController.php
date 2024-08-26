<?php

namespace TallStackUi\Http\Controllers;

use Exception;
use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TallStackUiAssetsController
{
    /** @throws Exception */
    public function script(?string $file = null): Response|BinaryFileResponse
    {
        $file = $this->fallback($file);

        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/'.$file, 'text/javascript');
    }

    /** @throws Exception */
    public function style(?string $file = null): Response|BinaryFileResponse
    {
        $file = $this->fallback($file);

        return Utils::pretendResponseIsFile(__DIR__.'/../../../dist/'.$file, 'text/css');
    }

    /** @throws Exception */
    private function fallback(string $file): string
    {
        $fallback = __ts_configuration('assets_fallback')->first();

        if (! $fallback) {
            return $file;
        }

        if (file_exists(__DIR__.'/../../../dist/'.$file)) {
            return $file;
        }

        $type = str_contains($file, '.css') ? 'css' : 'js';
        $plugin = request()->query('plugin');

        $files = collect(scandir(__DIR__.'/../../../dist'))
            ->filter(fn (string $file) => preg_match('/\.'.$type.'$/', $file))
            ->filter(function (string $file) use ($plugin) {
                if (! $plugin) {
                    return $file;
                }

                return str_contains($file, $plugin);
            })
            ->toArray();

        return rescue(fn () => reset($files), $file, false);
    }
}
