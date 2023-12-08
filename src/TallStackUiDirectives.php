<?php

namespace TallStackUi;

class TallStackUiDirectives
{
    public function script(): string
    {
        $route = route('tallstackui.script', [
            'file' => tallstackui_vite_manifest('js/tallstackui.js'),
        ]);

        $style = $this->style();

        return "<!--TallStackUI Script--><script src=\"$route\" defer></script>$style";
    }

    private function style(): string
    {
        $route = route('tallstackui.style', [
            'file' => tallstackui_vite_manifest('js/tallstackui.js', 'css')[0],
        ]);

        return "<!--TallStackUI CSS--><link href=\"{$route}\" rel=\"stylesheet\" type=\"text/css\">";
    }
}
