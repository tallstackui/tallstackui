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

    /**
     * Apply assets fallback feature.
     *
     * @throws Exception
     */
    private function fallback(string $file): string
    {
        if (__ts_configuration('assets_fallback')->first() === false || file_exists(__DIR__.'/../../../dist/'.$file)) {
            return $file;
        }

        $type = str_contains($file, '.css') ? 'css' : 'js';
        $plugin = (string) request()->query('plugin');

        // We get all files from the dist directory and filter them according to their type
        // and also whether the request is for a "plugin" and whether the file contains the plugin name.
        $files = collect(scandir(__DIR__.'/../../../dist'))
            ->filter(fn (string $file) => preg_match('/\.'.$type.'$/', $file))
            ->filter(function (string $file) use ($plugin) {
                if (blank($plugin)) {
                    return $file;
                }

                return str_contains($file, $plugin);
            })
            ->toArray();

        return rescue(fn () => reset($files), $file, false);
    }
}
