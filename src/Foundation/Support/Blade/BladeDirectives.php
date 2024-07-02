<?php

namespace TallStackUi\Foundation\Support\Blade;

use Illuminate\Support\Facades\Blade;
use TallStackUi\Facades\TallStackUi as Facade;

class BladeDirectives
{
    public static function register(): void
    {
        Blade::directive('tallStackUiScript', fn (): string => Facade::directives()->script());

        Blade::directive('tallStackUiStyle', fn (): string => Facade::directives()->style());

        Blade::directive('tallStackUiSetup', function (): string {
            $script = Facade::directives()->script();
            $style = Facade::directives()->style();

            return "{$script}\n{$style}";
        });

        // The objective of this directive is to allow interaction with contents of the table
        // component. The  concept was taken from konradkalemba/blade-components-scoped-slots.
        Blade::directive('interact', function (mixed $expression): string {
            $directive = array_map(trim(...), preg_split('/,(?![^(]*[)])/', $expression));
            $directive[1] ??= ''; // Prevents the error "Undefined key: 1" when the parameter is not defined.

            [$name, $arguments] = $directive;

            $parameters = collect(array_flip($directive))->except($name, $arguments)
                ->flip()
                ->push('$__env')
                ->implode(',');

            $name = str_replace('.', '_', $name);

            return "<?php \$__env->slot({$name}, function({$arguments}) use ({$parameters}) { ?>";
        });

        Blade::directive('endinteract', fn (): string => '<?php }); ?>');

        Blade::precompiler(fn (string $string): string => preg_replace_callback('/<\s*tallstackui\:(setup|script|style)\s*\/?>/', function (array $matches): string {
            $script = Facade::directives()->script();
            $style = Facade::directives()->style();

            return match ($matches[1]) { // @phpstan-ignore-line
                'setup' => "{$script}\n{$style}",
                'script' => $script,
                'style' => $style,
            };
        }, $string));
    }

    public function script(): string
    {
        $manifest = $this->manifest('js/tallstackui.js');
        $js = $manifest['file'];

        $html = $this->format($js);

        // This was created to solve problems linked to custom CSS from plugins like Tippy.js. If
        // we have a custom css, we can load it into JS, and it will build to extra CSS. As the
        // extra CSS is not load by Vite from the project that uses TallStackUI we need to deliver
        // the CSS automatically through the <tallstackui:script /> or @tallStackUiScript directive
        if ($css = ($manifest['css'][0] ?? null)) {
            $html .= $this->format($css);
        }

        return $html;
    }

    public function style(): string
    {
        return $this->format($this->manifest('src/resources/css/tallstackui.css', 'file'));
    }

    private function format(string $file): string
    {
        return (match (true) { // @phpstan-ignore-line
            str_ends_with($file, '.js') => fn () => "<script src=\"/tallstackui/script/{$file}\" defer></script>",
            str_ends_with($file, '.css') => fn () => "<link href=\"/tallstackui/style/{$file}\" rel=\"stylesheet\" type=\"text/css\">",
        })();
    }

    private function manifest(string $file, ?string $index = null): string|array
    {
        $content = json_decode(file_get_contents(__DIR__.'/../../../../dist/.vite/manifest.json'), true);

        return data_get($content[$file], $index);
    }
}
