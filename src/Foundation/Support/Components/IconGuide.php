<?php

namespace TallStackUi\Foundation\Support\Components;

//TODO: facade this???
use TallStackUi\View\Components\Icon;

class IconGuide
{
    // Supported icons
    public const AVAILABLE = [
        'heroicons' => [
            'solid',
            'outline',
        ],
        'phosphoricons' => [
            'thin',
            'light',
            'regular',
            'bold',
            'fill',
            'duotone',
        ],
    ];

    // Guide for the internal icons
    private const GUIDE = [
        'heroicons' => [
            'x-mark' => 'x-mark',
            'eye' => 'eye',
        ],
        'phosphoricons' => [
            'x-mark' => 'x',
            'eye' => 'eye',
        ],
    ];

    public static function for(Icon $component): string
    {
        $config = collect(config('tallstackui.icons'));

        $type = $config->get('type');
        $style = $config->get('style');

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (in_array($attribute, self::AVAILABLE[$type])) {
                $style = $attribute;
            }
        }

        $base = str_replace('components', '', $component->blade()->name());

        return sprintf(
            '%s.%s.%s.%s',
            $base,
            $type,
            $style,
            $component->icon ?? $component->name
        );
    }
}
