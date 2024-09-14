<?php

namespace TallStackUi\Foundation\Support\Icons;

use Exception;

class IconGuide
{
    /**
     * Supported icon libraries.
     */
    public const Supported = [
        'heroicons',
        'phosphoricons',
        'google',
        'tablericons',
        'lucide',
    ];

    /**
     * Resolve the given icon from the type and return the icon.
     *
     * @throws Exception
     */
    public static function get(string $type, string $key): ?string
    {
        return rescue(fn () => self::resolve($type)['icons'][$key], null, false);
    }

    // https://fonts.google.com/icons
    public static function google(): array
    {
        return [
            'styles' => ['default'],
            'icons' => [
                'arrow-uturn-left' => 'undo',
                'arrow-uturn-right' => 'redo',
                'arrow-path' => 'sync',
                'arrow-trending-up' => 'trending-up',
                'arrow-trending-down' => 'trending-down',
                'arrow-up-tray' => 'upload',
                'calendar' => 'calendar-today',
                'check' => 'check',
                'check-circle' => 'check-circle',
                'chevron-down' => 'expand-more',
                'chevron-left' => 'chevron-left',
                'chevron-right' => 'chevron-right',
                'chevron-up' => 'expand-less',
                'chevron-up-down' => 'unfold-more',
                'clipboard' => 'content-paste',
                'clipboard-document' => 'content-copy',
                'cloud-arrow-up' => 'cloud-upload',
                'clock' => 'schedule',
                'document-check' => 'assignment',
                'document-text' => 'description',
                'document-arrow-down' => 'file-save',
                'exclamation-circle' => 'info',
                'eye' => 'visibility',
                'eye-slash' => 'visibility-off',
                'information-circle' => 'info',
                'magnifying-glass' => 'search',
                'minus' => 'remove',
                'moon' => 'dark-mode',
                'photo' => 'image',
                'plus' => 'add',
                'question-mark-circle' => 'help',
                'swatch' => 'palette',
                'sun' => 'light-mode',
                'trash' => 'delete',
                'x-circle' => 'cancel',
                'x-mark' => 'close',
            ],
        ];
    }

    // https://heroicons.com/
    public static function hero(): array
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

    // https://lucide.dev/icons/
    public static function lucide(): array
    {
        return [
            'styles' => ['default'],
            'icons' => [
                'arrow-path' => 'rotate-cw',
                'arrow-trending-up' => 'trending-up',
                'arrow-trending-down' => 'trending-down',
                'arrow-up-tray' => 'upload',
                'calendar' => 'calendar-days',
                'check' => 'check',
                'check-circle' => 'circle-check',
                'chevron-down' => 'chevron-down',
                'chevron-left' => 'chevron-left',
                'chevron-right' => 'chevron-right',
                'chevron-up' => 'chevron-up',
                'chevron-up-down' => 'chevrons-up-down',
                'clipboard' => 'clipboard',
                'clipboard-document' => 'clipboard-check',
                'cloud-arrow-up' => 'cloud-upload',
                'clock' => 'clock',
                'document-check' => 'file-check',
                'document-text' => 'file-text',
                'exclamation-circle' => 'triangle-alert',
                'eye' => 'eye',
                'eye-slash' => 'eye-off',
                'information-circle' => 'info',
                'magnifying-glass' => 'search',
                'minus' => 'minus',
                'moon' => 'moon',
                'photo' => 'photo',
                'plus' => 'plus',
                'question-mark-circle' => 'circle-help',
                'swatch' => 'palette',
                'sun' => 'sun',
                'trash' => 'trash',
                'x-circle' => 'circle-x',
                'x-mark' => 'x',
            ],
        ];
    }

    // https://phosphoricons.com/
    public static function phosphor(): array
    {
        return [
            'styles' => [
                'thin',
                'light',
                'regular',
                'bold',
                'duotone',
            ],
            'icons' => [
                'arrow-uturn-left' => 'arrow-u-up-left',
                'arrow-uturn-right' => 'arrow-u-up-right',
                'arrow-path' => 'arrows-clockwise',
                'arrow-trending-up' => 'trend-up',
                'arrow-trending-down' => 'trend-down',
                'arrow-up-tray' => 'upload-simple',
                'calendar' => 'calendar-blank',
                'check' => 'check',
                'check-circle' => 'check-circle',
                'chevron-down' => 'caret-down',
                'chevron-left' => 'caret-left',
                'chevron-right' => 'caret-right',
                'chevron-up' => 'caret-up',
                'chevron-up-down' => 'caret-up-down',
                'clipboard' => 'clipboard',
                'clipboard-document' => 'copy-simple',
                'cloud-arrow-up' => 'cloud-arrow-up',
                'clock' => 'clock',
                'document-check' => 'clipboard-text',
                'document-text' => 'file-text',
                'document-arrow-down' => 'file-arrow-down',
                'exclamation-circle' => 'info',
                'eye' => 'eye',
                'eye-slash' => 'eye-slash',
                'information-circle' => 'info',
                'magnifying-glass' => 'magnifying-glass',
                'minus' => 'minus',
                'moon' => 'moon',
                'photo' => 'image',
                'plus' => 'plus',
                'question-mark-circle' => 'question',
                'swatch' => 'swatches',
                'sun' => 'sun',
                'trash' => 'trash',
                'x-circle' => 'x-circle',
                'x-mark' => 'x',
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
            'hero', 'heroicons' => self::hero(),
            'google' => self::google(),
            'lucide' => self::lucide(),
            'phosphor', 'phosphoricons' => self::phosphor(),
            'tabler', 'tablericons' => self::tabler(),
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

    /**
     * Determine if the given type is supported.
     */
    public static function supported(string $type): bool
    {
        return in_array($type, self::Supported);
    }

    // https://tabler.io/icons
    public static function tabler(): array
    {
        return [
            'styles' => ['default'],
            'icons' => [
                'arrow-uturn-left' => 'arrow-back-up',
                'arrow-uturn-right' => 'arrow-forward-up',
                'arrow-path' => 'refresh',
                'arrow-trending-up' => 'trending-up',
                'arrow-trending-down' => 'trending-down',
                'arrow-up-tray' => 'upload',
                'calendar' => 'calendar-event',
                'check' => 'check',
                'check-circle' => 'circle-check',
                'chevron-down' => 'chevron-down',
                'chevron-left' => 'chevron-left',
                'chevron-right' => 'chevron-right',
                'chevron-up' => 'chevron-up',
                'chevron-up-down' => 'selector',
                'clipboard' => 'clipboard',
                'clipboard-document' => 'clipboard-check',
                'cloud-arrow-up' => 'cloud-upload',
                'clock' => 'clock',
                'document-check' => 'file-check',
                'document-text' => 'file-text',
                'document-arrow-down' => 'file-download',
                'exclamation-circle' => 'exclamation-circle',
                'eye' => 'eye',
                'eye-slash' => 'eye-off',
                'information-circle' => 'info-circle',
                'magnifying-glass' => 'search',
                'minus' => 'minus',
                'moon' => 'moon',
                'photo' => 'photo',
                'plus' => 'plus',
                'question-mark-circle' => 'help',
                'swatch' => 'color-swatch',
                'sun' => 'sun',
                'trash' => 'trash',
                'x-circle' => 'circle-x',
                'x-mark' => 'x',
            ],
        ];
    }
}
