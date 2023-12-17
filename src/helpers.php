<?php

use Illuminate\Support\Facades\File;
use TallStackUi\Foundation\Attributes\SoftPersonalization;

if (! function_exists('tallstackui_components_soft_personalized')) {
    function tallstackui_components_soft_personalized(): array
    {
        // This strategy was adopted by deep personalization. If the original component
        // class was changed to some custom component, we would have some problems.
        return collect(File::allFiles(__DIR__.'/View/Components'))
            // Mapping all component classes
            ->map(function (SplFileInfo $file) {
                return 'TallStackUi\\View\\'.str($file->getPathname())->after('View/')
                    ->remove('.php')
                    ->replace('/', '\\')
                    ->value();
            })
            // Filtering only components with SoftPersonalization attribute
            ->filter(fn (string $component) => (new ReflectionClass($component))->getAttributes(SoftPersonalization::class)) // @phpstan-ignore-line
            // Mapping to return the soft personalization key and the component class
            ->mapWithKeys(function (string $component) {
                $reflect = new ReflectionClass($component);
                $attribute = $reflect->getAttributes(SoftPersonalization::class)[0];

                return [$attribute->newInstance()->key() => $reflect->getName()];
            })
            ->toArray();
    }
}
