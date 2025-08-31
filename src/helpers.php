<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;
use TallStackUi\TallStackUiComponent;

if (! function_exists('__ts_class_collection')) {
    /**
     * Creates a collection with metadata about the class color.
     */
    function __ts_class_collection(string $component): Collection
    {
        $bypass = [
            // class => replacement
            'Circle' => 'Button',
        ];

        $collect = collect();

        if (($namespace = config('tallstackui.color_classes_namespace')) === null) {
            return $collect;
        }

        // Bypass created to solve the issue of button.circle not receiving color in v2.
        // Reported issue: https://github.com/tallstackui/tallstackui/issues/1102. In v3
        // the Circle Button should have your own colors class.
        $color = in_array($component, array_keys($bypass)) ? $bypass[$component] : $component;

        $collect->put('component', $component);
        $collect->put('namespace', $namespace);
        $collect->put('file', $color.'Colors.php');
        $collect->put('file_raw', $color.'Colors');
        $collect->put('stub', __DIR__.'/Foundation/Support/Colors/Stubs/'.$collect->get('file_raw').'.stub');

        $class = $namespace.'\\'.$collect->get('file_raw');

        $collect->put('app_path', app_path(str($namespace)->remove('App\\')->replace('\\', '/')->value().'/'.$collect->get('file')));
        $collect->put('file_exists', $exists = file_exists($collect->get('app_path')));
        $collect->put('instance', $exists ? new $class : null);

        return $collect;
    }
}

if (! function_exists('__ts_validation_exception')) {
    /**
     * Throw a validation exception for the component rendering beautiful messages.
     */
    function __ts_validation_exception(TallStackUiComponent|string $component, string $message): mixed
    {
        $title = str(is_string($component) ? $component : $component::class)
            ->after('TallStackUi\\View\\Components\\')
            ->title()
            ->value();

        throw new InvalidArgumentException(sprintf('[TallStackUI] %s: %s', $title, $message));
    }
}

if (! function_exists('__ts_filter_components_using_attribute')) {
    /**
     * Filter all components that use the given attribute.
     */
    function __ts_filter_components_using_attribute(string $attribute): Collection
    {
        return collect(File::allFiles(__DIR__.'/View/Components'))
            ->map(fn (SplFileInfo $file) => 'TallStackUi\\View\\'.str($file->getPathname())->after('View/')
                ->remove('.php')
                ->replace('/', '\\')
                ->value())
            ->filter(fn (string $component) => (new ReflectionClass($component))->getAttributes($attribute)); // @phpstan-ignore-line
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
        $result = array_search($component, __ts_soft_personalization_components());

        if (! $result) {
            throw new Exception("Component [{$component}] is not allowed to be personalized");
        }

        return $result;
    }
}

if (! function_exists('__ts_soft_personalization_components')) {
    /**
     * Get all components that use the SoftPersonalization attribute.
     */
    function __ts_soft_personalization_components(): array
    {
        return __ts_filter_components_using_attribute(SoftPersonalization::class)
            ->mapWithKeys(function (string $component): array {
                $reflect = new ReflectComponent($component);

                /** @var SoftPersonalization $instance */
                $instance = $reflect->attribute(SoftPersonalization::class)->newInstance();

                return [$instance->prefixed() => $reflect->class()->getName()];
            })
            ->toArray();
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
