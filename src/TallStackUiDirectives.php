<?php

namespace TallStackUi;

class TallStackUiDirectives
{
    public function script(): string
    {
        $manifest = tallstackui_vite_manifest('js/tallstackui.js');
        $js = $manifest['file'];

        $html = $this->build($js);

        // This was created to solve problems linked to custom CSS from plugins like Tippy.js. If
        // we have a custom css, we can load it into JS, and it will build to extra CSS. As the
        // extra CSS is not load by Vite from the project that uses TallStackUI we need to deliver
        // the CSS automatically through the <tallstackui:script /> or @tallStackUiScript directive
        if ($css = ($manifest['css'][0] ?? null)) {
            $html .= $this->build($css);
        }

        return $html;
    }

    public function style(): string
    {
        return $this->build(tallstackui_vite_manifest('src/resources/css/tallstackui.css', 'file'));
    }

    protected function build(string $file): string
    {
        return (match (true) { // @phpstan-ignore-line
            str_ends_with($file, '.js') => function () use ($file) {
                $route = route('tallstackui.script', ['file' => $file]);

                return "<script src=\"{$route}\" defer></script>";
            },
            str_ends_with($file, '.css') => function () use ($file) {
                $route = route('tallstackui.style', ['file' => $file]);

                return "<link href=\"{$route}\" rel=\"stylesheet\" type=\"text/css\">";
            },
        })();
    }
}
