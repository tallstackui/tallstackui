<?php

namespace TallStackUi\Foundation\Support\Components;

//TODO: facade this???
use Exception;
use Illuminate\Support\Facades\View;
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

    /** @throws Exception */
    public static function for(Icon $component): string
    {
        $config = collect(config('tallstackui.icons'));

        $type = $config->get('type');
        $style = $config->get('style');

        if (! in_array($type, array_keys(self::AVAILABLE))) {
            throw new Exception("The icon type [$type] is not supported.");
        }

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (in_array($attribute, self::AVAILABLE[$type])) {
                $style = $attribute;
            }
        }

        $base = str_replace('components', '', $view = $component->blade()->name());
        $icon = sprintf('%s.%s.%s', $type, $style, $component->icon ?? $component->name);

        if (! View::exists($view.'.'.$icon)) {
            throw new Exception("The icon [$icon] does not exist.");
        }

        return $base.'.'.$icon;
    }
}
