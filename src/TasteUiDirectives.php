<?php

namespace TasteUi;

use Illuminate\View\ComponentAttributeBag;

class TasteUiDirectives
{
    public function scripts(bool $absolute = true, array $attributes = []): string
    {
        $route = route('tasteui.scripts', absolute: $absolute);
        $this->manifest("tasteui.js",$route);

        $attributes = new ComponentAttributeBag($attributes);

        return <<<HTML
            <script src="{$route}" defer {$attributes->toHtml()}></script>
        HTML;
    }

    public function styles(bool $absolute = true): string
    {
        $route = route('wireui.assets.styles', $parameters = [], $absolute);
        $this->manifest('tasteui.css', $route);

        return "<link href=\"{$route}\" rel=\"stylesheet\" type=\"text/css\">";
    }

    private function manifest(string $file, string &$route): void
    {
        if (!file_exists($path = __DIR__ . '/../dist/mix-manifest.json')) {
            return;
        }

        $manifest = json_decode(file_get_contents($path), $assoc = true);
        $version  = last(explode('=', $manifest["/{$file}"]));

        $route .= "?id={$version}";
    }
}