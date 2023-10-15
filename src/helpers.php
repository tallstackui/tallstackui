<?php

use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;

if (! function_exists('tallstackui_personalization')) {
    function tallstackui_personalization(string $component, array $personalization): array
    {
        $blocks = TallStackUi::personalize($component)
            ->instance()
            ->toArray();

        return Arr::only(array_merge($personalization, $blocks), array_keys($personalization));
    }
}
