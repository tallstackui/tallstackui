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
        return collect(File::allFiles(__DIR__.'/View/Components'))
            ->map(function (SplFileInfo $file) {
                return 'TallStackUi\\View\\'.str($file->getPathname())->after('View/')
                    ->remove('.php')
                    ->replace('/', '\\')
                    ->value();
            })
            ->filter(fn (string $component) => (new ReflectionClass($component))->getAttributes(SoftPersonalization::class)) // @phpstan-ignore-line
            ->mapWithKeys(function (string $component) {
                $reflect = new ReflectionClass($component);
                $attribute = $reflect->getAttributes(SoftPersonalization::class)[0];

                return [$attribute->newInstance()->get() => $reflect->getName()];
            })
            ->toArray();
    }
}
