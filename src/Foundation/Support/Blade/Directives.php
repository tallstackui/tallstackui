<?php

namespace TallStackUi\Foundation\Support\Blade;

use Illuminate\Support\Facades\Blade;
use TallStackUi\Facades\TallStackUi as Facade;

class Directives
{
    /**
     * Register the Blade directives.
     */
    public static function register(): void
    {
        Blade::directive('tallStackUiScript', fn (): string => Facade::directives()->script());

        Blade::directive('tallStackUiStyle', fn (mixed $expression): string => Facade::directives()->style($expression));

        Blade::directive('tallStackUiSetup', function (mixed $expression): string {
            $script = Facade::directives()->script();
            $style = Facade::directives()->style($expression);

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

            return "<?php \$loop = null; \$__env->slot({$name}, function({$arguments}) use ({$parameters}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
        });

        Blade::directive('endinteract', fn (): string => '<?php }); ?>');

        Blade::precompiler(fn (string $string): string => preg_replace_callback('/<\s*tallstackui\:(setup|script|style)(\s+[a-zA-Z0-9_-]+(?:\s+[a-zA-Z0-9_-]+)*)?\s*\/?>/', function (array $matches): string {
            $script = Facade::directives()->script();
            $style = Facade::directives()->style($matches);

            return match ($matches[1]) {
                'setup' => "{$script}\n{$style}",
                'script' => $script,
                'style' => $style,
            };
        }, $string));
    }

    /**
     * Get the HTML that represents the script load.
     */
    public function script(): string
    {
        $manifest = $this->manifest('js/tallstackui.js');
        $js = $manifest['file'];

        $html = $this->format($js);

        // This was created to solve problems linked to custom CSS from plugins like Tippy.js. If
        // we have a custom css, we can load it into JS, and it will build to extra CSS. As the
        // extra CSS is not load by Vite from the project that uses TallStackUI we need to deliver
        // the CSS automatically through the <tallstackui:script /> or @tallStackUiScript directive
        if (($manifest['css'][0] ?? null) !== null) {
            $html .= $this->format($this->manifest('node_modules/tippy.js/dist/tippy.css', 'file'));
        }

        return $html;
    }

    /**
     * Get the HTML that represents the style load.
     */
    public function style(mixed $matches = null): string
    {
        $version = null;

        if ($matches) {
            $version = is_array($matches) ? trim(data_get($matches, 2)) : str_replace('\'', '', $matches);
        }

        return $this->format(match ($version) {
            'v4' => 'tallstackui.css',
            default => $this->manifest('css/v3.css', 'file'),
        });
    }

    /**
     * Format according to the file extension.
     */
    private function format(string $file): string
    {
        return (match (true) { // @phpstan-ignore-line
            str_ends_with($file, '.js') => fn () => "<script src=\"/tallstackui/script/{$file}\" defer></script>",
            str_ends_with($file, '.css') => fn () => "<link href=\"/tallstackui/style/{$file}\" rel=\"stylesheet\" type=\"text/css\">",
        })();
    }

    /**
     * Load the manifest file and retrieve the desired data.
     */
    private function manifest(string $file, ?string $index = null): string|array
    {
        $content = json_decode(file_get_contents(__DIR__.'/../../../../dist/.vite/manifest.json'), true);

        return data_get($content[$file], $index);
    }
}
