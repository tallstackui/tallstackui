<?php

namespace TallStackUi;

class TallStackUiDirectives
{
    public function script(bool $absolute = true): string
    {
        $route = route('tallstackui.script', [
            'file' => $this->vite('js/tallstackui.js'),
        ], absolute: $absolute);

        return "<script src=\"$route\" defer></script>";
    }

    public function style(bool $absolute = true): string
    {
        $route = route('tallstackui.style', [
            'file' => $this->vite('src/resources/css/tallstackui.css'),
        ], absolute: $absolute);

        return "<link href=\"{$route}\" rel=\"stylesheet\" type=\"text/css\">";
    }

    private function vite(string $file): string
    {
        $file = tallstackui_load_vite_manitefest($file);

        return $file;
    }
}
