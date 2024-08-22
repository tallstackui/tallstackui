<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
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
        'lucide' => [
            'default',
        ],
    ];

    // TODO: move to a enum class
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
        // https://lucide.dev/icons/
        'lucide' => [
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

    /**
     * The icon configuration.
     */
    protected static Collection $configuration;

    /**
     * Determine if the icon is custom.
     */
    protected static bool $custom = false;

    /** @throws Exception|InappropriateIconGuideExecution */
    public static function build(Component $component, ?string $path = null): string
    {
        InappropriateIconGuideExecution::validate($component::class);

        self::configuration();

        $type = self::$configuration->get('type');
        $style = self::$configuration->get('style');

        self::validate($type);

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (self::$custom || ! in_array($attribute, self::AVAILABLE[$type])) {
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

        if (self::$custom) {
            $icon = sprintf('%s.%s', str_replace('/', '.', explode(':', (string) $type)[1]), $name);

            // When the custom icon does not exist in the custom icons and the
            // fallback is enabled, we use the internal icons to avoid exceptions.
            if (
                ! View::exists('components.'.$icon) &&
                data_get(self::$configuration->get('custom'), 'fallback', true) === true &&
                isset(self::GUIDE['heroicons'][$name])
            ) {
                return sprintf('tallstack-ui::icon.heroicons.%s.%s', $style === 'outline' ? $style : 'solid', $name);
            }

            return $icon;
        }

        $component = sprintf('%s.%s.%s', $type, $style, $name);

        return $path ? $path.$component : $component;
    }

    /**
     * Determine internal icons using the guide.
     *
     * @throws Exception
     */
    public static function internal(string $key): string
    {
        self::configuration();

        self::validate($type = self::$configuration->get('type'));

        $guide = null;

        // We start by returning $icon because when we are
        // dealing with custom icons and cannot find the
        // guide for a particular icon, we use the default.
        if (self::$custom) {
            $type = str_replace('/', '.', explode(':', (string) $type)[1]);
            $guide = self::$configuration->get('custom')['guide'][$key] ?? null;
        }

        return $guide ?? self::GUIDE[$type][$key] ?? $key;
    }

    /**
     * Get the configuration for icons and determine if it is custom.
     *
     * @throws Exception
     */
    private static function configuration(): void
    {
        self::$configuration = __ts_configuration('icons');

        self::$custom = str_contains((string) self::$configuration->get('type'), 'custom:') && self::$configuration->get('custom') !== null;
    }

    /** @throws Exception */
    private static function validate(string $type): void
    {
        if (in_array($type, array_keys(self::AVAILABLE)) || self::$custom) {
            return;
        }

        throw new Exception("The icon type [$type] is not supported.");
    }
}
