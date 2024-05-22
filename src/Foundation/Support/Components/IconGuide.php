<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;

class IconGuide
{
    final public const AVAILABLE = [
        'heroicons' => [
            'solid',
            'outline',
        ],
        'phosphoricons' => [
            'thin',
            'light',
            'regular',
            'bold',
            'duotone',
        ],
        'google' => [
            'default',
        ],
        'tablericons' => [
            'default',
        ],
    ];

    // The idea of this constant is to be used as a map of internal icons in
    // components. As new icons are supported, this guide should be updated
    // and icon references should use generic names when possible.
    private const GUIDE = [
        // https://heroicons.com/
        'heroicons' => [
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
        // https://phosphoricons.com/
        'phosphoricons' => [
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
        // https://fonts.google.com/icons
        'google' => [
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
        // https://tabler.io/icons
        'tablericons' => [
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

    /** @throws Exception|InappropriateIconGuideExecution */
    public static function build(Component $component): string
    {
        InappropriateIconGuideExecution::validate($component::class);

        $configuration = self::configuration();

        $type = $configuration->get('type');
        $style = $configuration->get('style');

        self::validate($type);

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (! in_array($attribute, self::AVAILABLE[$type])) {
                continue;
            }

            // When some attribute matches one of the keys
            // available in the supported icons, then we want
            // to override the style through run time.
            $style = $attribute;
        }

        $name = $component->icon ?? $component->name; // @phpstan-ignore-line

        // For phosphoricons when the style is different from
        // "regular" we need to add the style right after the
        // name due to the way phosphoricons exports the files.
        if ($type === 'phosphoricons' && $style !== 'regular') {
            $name = $name.'-'.$style;
        }

        return sprintf('%s.%s.%s', $type, $style, $name);
    }

    /**
     * Determine internal icons using the guide.
     *
     * @throws Exception
     */
    public static function internal(string $key): string
    {
        $configuration = self::configuration();

        self::validate($type = $configuration->get('type'));

        return self::GUIDE[$type][$key] ?? $key;
    }

    private static function configuration(): Collection
    {
        return collect(config('tallstackui.icons'));
    }

    /** @throws Exception */
    private static function validate(string $type): void
    {
        if (in_array($type, array_keys(self::AVAILABLE))) {
            return;
        }

        throw new Exception("The icon type [$type] is not supported.");
    }
}
