<?php

namespace TallStackUi\Foundation\Http\Controllers;

use Exception;
use Livewire\Drawer\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TallStackUiAssetsController
{
    /**
     * The path to the dist directory.
     */
    protected const DIST_PATH = __DIR__.'/../../../../dist';

    /** @throws Exception */
    public function script(?string $file = null): Response|BinaryFileResponse
    {
        $file = $this->fallback($file);

        return Utils::pretendResponseIsFile(self::DIST_PATH.'/'.$file, 'text/javascript');
    }

    /** @throws Exception */
    public function style(?string $file = null): Response|BinaryFileResponse
    {
        $file = $file === 'tallstackui.css'
            ? 'tallstackui.css' // TailwindCSS v4
            : $this->fallback($file);

        return Utils::pretendResponseIsFile(self::DIST_PATH.'/'.$file, 'text/css');
    }

    /**
     * Apply assets fallback feature.
     *
     * @throws Exception
     */
    private function fallback(string $file): string
    {
        $config = config('tallstackui.assets_fallback');

        if (blank($config) || $config === false || file_exists(self::DIST_PATH.'/'.$file)) {
            return $file;
        }

        $string = str(request()->url())->afterLast('/');
        $type = request()->segment(2) === 'script' ? 'js' : 'css';
        $plugin = $string->contains('tallstackui') ? null : $string->before('-');

        // We get all files from the dist directory and filter them according to their type
        // and also whether the request is for a "plugin" and whether the file contains the plugin name.
        $files = collect(scandir(self::DIST_PATH))
            ->filter(fn (string $file) => preg_match('/\.'.$type.'$/', $file))
            ->filter(function (string $file) use ($plugin): bool {
                if (blank($plugin)) {
                    return true;
                }

                return str_contains($file, (string) $plugin);
            })
            ->toArray();

        return rescue(fn () => reset($files), $file, false);
    }
}
