<?php

namespace TallStackUi\Foundation\Support\Icons;

use Exception;

class IconGuide
{
    /**
     * Resolve the given icon from the type and return the icon.
     *
     * @throws Exception
     */
    public static function get(string $type, string $key): ?string
    {
        return rescue(fn () => self::resolve($type)['icons'][$key], null, false);
    }

    // https://heroicons.com/
    public static function heroicons(): array
    {
        return [
            'styles' => ['outline', 'solid'],
            'icons' => [
                'arrow-uturn-left' => 'arrow-uturn-left',
                'arrow-uturn-right' => 'arrow-uturn-right',
                'arrow-path' => 'arrow-path',
                'arrow-trending-up' => 'arrow-trending-up',
                'arrow-trending-down' => 'arrow-trending-down',
                'arrow-up-tray' => 'arrow-up-tray',
                'bars-4' => 'bars-4',
                'calendar' => 'calendar',
                'check' => 'check',
                'check-circle' => 'check-circle',
                'chevron-down' => 'chevron-down',
                'chevron-left' => 'chevron-left',
                'chevron-right' => 'chevron-right',
                'chevron-up' => 'chevron-up',
                'chevron-up-down' => 'chevron-up-down',
                'clipboard' => 'clipboard',
                'clipboard-document' => 'clipboard-document',
                'cloud-arrow-up' => 'cloud-arrow-up',
                'clock' => 'clock',
                'document-check' => 'document-check',
                'document-text' => 'document-text',
                'document-arrow-down' => 'document-arrow-down',
                'exclamation-circle' => 'exclamation-circle',
                'eye' => 'eye',
                'eye-slash' => 'eye-slash',
                'information-circle' => 'information-circle',
                'magnifying-glass' => 'magnifying-glass',
                'minus' => 'minus',
                'moon' => 'moon',
                'photo' => 'photo',
                'plus' => 'plus',
                'question-mark-circle' => 'question-mark-circle',
                'swatch' => 'swatch',
                'sun' => 'sun',
                'trash' => 'trash',
                'x-circle' => 'x-circle',
                'x-mark' => 'x-mark',
            ],
        ];
    }

    /**
     * Resolve the given icon guide from the type.
     *
     * @throws Exception
     */
    public static function resolve(string $type): array
    {
        return match ($type) {
            'hero', 'heroicons' => self::heroicons(),
            default => throw new Exception("The icon guide [{$type}] is not supported."),
        };
    }

    /**
     * Resolve the given icon from the type and return the styles.
     *
     * @throws Exception
     */
    public static function styles(string $type): array
    {
        return self::resolve($type)['styles'];
    }
}
