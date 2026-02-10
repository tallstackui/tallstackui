<?php

use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Globals;
use TallStackUi\Support\Miscellaneous\ReflectComponent;
use TallStackUi\TallStackUiComponent;

if (! function_exists('__ts_get_component_configuration')) {
    /**
     * Get the component configuration from the config file.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_get_component_configuration(string $component, ?string $key = null, bool $flush = false): mixed
    {
        static $map = null;

        if ($flush) {
            $map = null;
        }

        if ($map === null) {
            $components = config('ts-ui.components');
            $arrays = array_filter($components, 'is_array');
            $map = array_combine(array_column($arrays, 0), array_column($arrays, 1));
        }

        if ($key !== null) {
            return $map[$component][$key] ?? null;
        }

        return $map[$component] ?? null;
    }
}

if (! function_exists('__ts_class_collection')) {
    /**
     * Creates an array with metadata about the class color.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_class_collection(string $component): array
    {
        $namespace = config('ts-ui.color_classes_namespace');

        if ($namespace === null) {
            return [];
        }

        $raw = $component.'Colors';
        $file = $raw.'.php';
        $path = app_path(str_replace('\\', '/', str_replace('App\\', '', $namespace)).'/'.$file);
        $exists = file_exists($path);

        return [
            'component' => $component,
            'namespace' => $namespace,
            'file' => $file,
            'file_raw' => $raw,
            'stub' => __DIR__.'/Support/Colors/Stubs/'.$raw.'.stub',
            'app_path' => $path,
            'file_exists' => $exists,
            'instance' => $exists ? new ($namespace.'\\'.$raw) : null,
        ];
    }
}

if (! function_exists('__ts_validation_exception')) {
    /**
     * Throw a validation exception for the component rendering beautiful messages.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_validation_exception(TallStackUiComponent|string $component, string $message): mixed
    {
        $class = is_string($component) ? $component : $component::class;

        $prefix = 'TallStackUi\\Components\\';

        $title = str_starts_with($class, $prefix) ? substr($class, strlen($prefix)) : $class;
        $title = preg_replace('/\\\\Component$/', '', $title);

        throw new InvalidArgumentException(sprintf('[TallStackUI] %s: %s', $title, $message));
    }
}

if (! function_exists('__ts_filter_components_using_attribute')) {
    /**
     * Filter all components that use the given attribute.
     */
    function __ts_filter_components_using_attribute(string $attribute): array
    {
        static $classes = null;
        static $filtered = [];

        if (isset($filtered[$attribute])) {
            return $filtered[$attribute];
        }

        if ($classes === null) {
            $classes = [];

            $scan = static function (string $dir, string $prefix) use (&$classes): void {
                if (! is_dir($dir)) {
                    return;
                }

                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

                foreach ($iterator as $file) {
                    if ($file->getFilename() !== 'Component.php') {
                        continue;
                    }

                    $relative = substr($file->getPathname(), strlen($dir) + 1, -4);
                    $classes[] = $prefix.str_replace('/', '\\', $relative);
                }
            };

            $scan(__DIR__.'/Components', 'TallStackUi\\Components\\');
        }

        return $filtered[$attribute] = array_filter($classes, fn (string $class): bool => (new ReflectionClass($class))->getAttributes($attribute) !== []);
    }
}

if (! function_exists('__ts_search_component')) {
    /**
     * Search for the component key in the components.
     *
     * @throws Exception
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_search_component(string $component): string
    {
        static $flipped = null;

        if ($flipped === null) {
            $flipped = array_flip(__ts_soft_customization_components());
        }

        return $flipped[$component] ?? throw new Exception("Component [{$component}] is not allowed to be customized");
    }
}

if (! function_exists('__ts_soft_customization_components')) {
    /**
     * Get all components that use the SoftPersonalization attribute.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_soft_customization_components(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $components = __ts_filter_components_using_attribute(SoftCustomization::class);
        $result = [];

        foreach ($components as $component) {
            $reflect = new ReflectComponent($component);

            /** @var SoftCustomization $instance */
            $instance = $reflect->attribute(SoftCustomization::class)->newInstance();

            $result[$instance->prefixed()] = $reflect->class()->getName();
        }

        return $cache = $result;
    }
}

if (! function_exists('__ts_global')) {
    /**
     * Check if a global is active for the given component.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_global(string $global, TallStackUiComponent|string $component): bool
    {
        return Globals::is($global, is_string($component) ? $component : $component::class);
    }
}

if (! function_exists('__ts_scope_container_key')) {
    /**
     * Creates the key that will be used to look up the
     * scope instance reference in the Laravel container.
     *
     * @internal This function should not be used outside the package.
     */
    function __ts_scope_container_key(string $component, string $key): string
    {
        return $component.'::scoped::'.strtolower($key);
    }
}
