<?php

namespace TallStackUi\Foundation\Support\Components;

//TODO: facade this???
use RuntimeException;

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

    public static function get(string $name): string
    {
        $icon = config('tallstackui.icons.type');

        if (! $icon) {
            throw new RuntimeException('Icon type is not set in the TallStackUI configuration file.');
        }

        return self::GUIDE[$icon][$name];
    }
}
