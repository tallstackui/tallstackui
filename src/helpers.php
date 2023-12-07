<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Personalizations\SoftPersonalization;

if (! function_exists('tallstackui_personalization')) {
    function tallstackui_personalization(string $component, array $personalization): array
    {
        $blocks = TallStackUi::personalize($component)->instance();

        return Arr::only(array_merge($personalization, $blocks->toArray()), array_keys($personalization));
    }
}

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

                return [$attribute->newInstance()->get() => $reflect->getName()];
            })
            ->toArray();
    }
}

if (! function_exists('tallstackui_load_vite_manitefest')) {
    function tallstackui_load_vite_manitefest(string $file, string $index = 'file'): string
    {
        $content = json_decode(file_get_contents(__DIR__.'/../dist/.vite/manifest.json'), true);

        return $content[$file][$index];
    }
}
