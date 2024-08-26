<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;

if (! function_exists('__ts_components')) {
    /**
     * Recursively maps all Blade components that use the
     * SoftPersonalization attribute to the soft personalization.
     */
    function __ts_components(): array
    {
        // This strategy was adopted by deep personalization. If the original component
        // class was changed to some custom component, we would have some problems.
        return collect(File::allFiles(__DIR__.'/View/Components'))
            ->map(fn (SplFileInfo $file) => 'TallStackUi\\View\\'.str($file->getPathname())->after('View/')
                ->remove('.php')
                ->replace('/', '\\')
                ->value())
            ->filter(fn (string $component) => (new ReflectionClass($component))->getAttributes(SoftPersonalization::class)) // @phpstan-ignore-line
            ->mapWithKeys(function (string $component) {
                $reflect = new ReflectComponent($component);

                return [$reflect->attribute()->newInstance()->key(prefix: true) => $reflect->class()->getName()];
            })
            ->toArray();
    }
}

if (! function_exists('__ts_search_component')) {
    /**
     * Search for the component key in the components.
     *
     * @throws Exception
     */
    function __ts_search_component(string $component): string
    {
        $result = array_search($component, __ts_components());

        if (! $result) {
            throw new Exception("Component [{$component}] is not allowed to be personalized");
        }

        return $result;
    }
}

if (! function_exists('__ts_scope_container_key')) {
    /**
     * Creates the key that will be used to look up the
     * scope instance reference in the Laravel container.
     */
    function __ts_scope_container_key(string $component, string $key): string
    {
        $key = str($key)->lower()
            ->snake('::')
            ->value();

        return $component.'::scoped::'.$key;
    }
}

if (! function_exists('__ts_class_collection')) {
    /**
     * Creates a collection with metadata about the class color.
     */
    function __ts_class_collection(string $component): Collection
    {
        $collect = collect();

        if (($namespace = config('tallstackui.color_classes_namespace')) === null) {
            return $collect;
        }

        $collect->put('component', $component);
        $collect->put('namespace', $namespace);
        $collect->put('file', $component.'Colors.php');
        $collect->put('file_raw', $component.'Colors');
        $collect->put('stub', __DIR__.'/Foundation/Components/Colors/Stubs/'.$collect->get('file_raw').'.stub');

        $class = $namespace.'\\'.$collect->get('file_raw');

        $collect->put('app_path', app_path(str($namespace)->remove('App\\')->replace('\\', '/')->value().'/'.$collect->get('file')));
        $collect->put('file_exists', $exists = file_exists($collect->get('app_path')));
        $collect->put('instance', $exists ? new $class : null);

        return $collect;
    }
}

if (! function_exists('__ts_configuration')) {
    /**
     * Creates a collection/array with the configuration values.
     *
     * @throws Exception
     */
    function __ts_configuration(?string $index = null, bool $collection = true): Collection|array
    {
        // Making sure we didn't accidentally add tallstackui.
        $index = str($index)->remove(['tallstackui', 'tallstackui.'])->value();

        $config = config('tallstackui');

        // Test if $index exists on the config array.
        if (! data_get($config, $index)) {
            throw new Exception("Configuration [{$index}] does not exist");
        }

        $result = $index ? collect(data_get($config, $index)) : collect($config);

        return $collection ? $result : $result->toArray();
    }
}
