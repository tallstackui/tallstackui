<?php

namespace TallStackUi\Support\Blade;

use Illuminate\Support\Facades\Blade;
use TallStackUi\Facades\TallStackUi as Facade;

class Directives
{
    private static ?array $cache = null;

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

        // The goal of this directive is to allow interaction with the contents of the table
        // component. The concept was taken from konradkalemba/blade-components-scoped-slots.
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
            $style = Facade::directives()->style();

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
        // we have a custom CSS, we can load it into JS, and it will build to extra CSS. As the
        // extra CSS is not loaded by Vite from the project that uses TallStackUI, we need to deliver
        // the CSS automatically through the <tallstackui:script /> or @tallStackUiScript directive
        if (($manifest['css'][0] ?? null) !== null) {
            $html .= $this->format($this->manifest('node_modules/tippy.js/dist/tippy.css', 'file'));
        }

        return $html;
    }

    /**
     * Get the HTML that represents the style load.
     */
    public function style(): string
    {
        return $this->format('tallstackui.css');
    }

    /**
     * Format, according to the file extension.
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
        self::$cache ??= json_decode(file_get_contents(__DIR__.'/../../../dist/.vite/manifest.json'), true);

        return data_get(self::$cache[$file], $index);
    }
}
