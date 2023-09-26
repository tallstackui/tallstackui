<?php

use Illuminate\Support\Arr;

if (!function_exists('tasteui_personalize')) {
    function tasteui_personalize(array $personalization, array $customization): array
    {
        return Arr::only(array_merge($customization, $personalization), array_keys($customization));
    }
}