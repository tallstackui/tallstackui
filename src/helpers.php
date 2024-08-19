<?php

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use TallStackUi\Foundation\Attributes\SoftPersonalization;

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
                $reflect = new ReflectionClass($component);
                $attribute = $reflect->getAttributes(SoftPersonalization::class)[0];

                return [$attribute->newInstance()->key() => $reflect->getName()];
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
