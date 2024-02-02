<?php

namespace TallStackUi\Foundation\Support\Components;

//TODO: facade this
class InternalIconGuide
{
    private const ICONS = [
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

        return self::ICONS[$icon][$name];
    }
}
