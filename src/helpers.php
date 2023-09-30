<?php

use Illuminate\Support\Arr;
use TasteUi\Facades\TasteUi;

if (! function_exists('tasteui_personalization')) {
    function tasteui_personalization(string $personalization, array $customization): array
    {
        $personalization = TasteUi::personalize($personalization)
            ->instance()
            ->toArray();

        return Arr::only(array_merge($customization, $personalization), array_keys($customization));
    }
}
