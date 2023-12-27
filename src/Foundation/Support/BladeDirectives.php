<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\Facades\Blade;
use TallStackUi\Facades\TallStackUi as Facade;

class BladeDirectives
{
    public static function register(): void
    {
        Blade::directive('tallStackUiScript', function (): string {
            return Facade::directives()->script();
        });

        Blade::directive('tallStackUiStyle', function (): string {
            return Facade::directives()->style();
        });

        Blade::directive('tallStackUiSetup', function (): string {
            $script = Facade::directives()->script();
            $style = Facade::directives()->style();

            return "{$script}\n{$style}";
        });

        // The objective of this directive is to allow interaction with contents of the table
        // component. The  concept was taken from konradkalemba/blade-components-scoped-slots.
        Blade::directive('column', function (mixed $expression): string {
            $directive = array_map('trim', preg_split('/,(?![^(]*[)])/', $expression));

            [$name, $arguments] = $directive;

            $parameters = collect(array_flip($directive))->except($name, $arguments)
                ->flip()
                ->push('$__env')
                ->implode(',');

            $name = str_replace('.', '_', $name);

            return "<?php \$__env->slot({$name}, function({$arguments}) use ({$parameters}) { ?>";
        });

        Blade::directive('endcolumn', function (): string {
            return '<?php }); ?>';
        });

        Blade::precompiler(function (string $string): string {
            return preg_replace_callback('/<\s*tallstackui\:(setup|script|style)\s*\/?>/', function (array $matches): string {
                $script = Facade::directives()->script();
                $style = Facade::directives()->style();

                return match ($matches[1]) { // @phpstan-ignore-line
                    'setup' => "{$script}\n{$style}",
                    'script' => $script,
                    'style' => $style,
                };
            }, $string);
        });
    }

    public function script(): string
    {
        $manifest = $this->manifest('js/tallstackui.js');
        $js = $manifest['file'];

        $html = $this->url($js);

        // This was created to solve problems linked to custom CSS from plugins like Tippy.js. If
        // we have a custom css, we can load it into JS, and it will build to extra CSS. As the
        // extra CSS is not load by Vite from the project that uses TallStackUI we need to deliver
        // the CSS automatically through the <tallstackui:script /> or @tallStackUiScript directive
        if ($css = ($manifest['css'][0] ?? null)) {
            $html .= $this->url($css);
        }

        return $html;
    }

    public function style(): string
    {
        return $this->url($this->manifest('src/resources/css/tallstackui.css', 'file'));
    }

    private function manifest(string $file, ?string $index = null): string|array
    {
        $content = json_decode(file_get_contents(__DIR__.'/../../../dist/.vite/manifest.json'), true);

        return data_get($content[$file], $index);
    }

    private function url(string $file): string
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
