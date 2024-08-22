<?php

namespace TallStackUi\Foundation\Support\Icons;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;

class IconGuideMap
{
    /**
     * The icon configuration.
     */
    protected static Collection $configuration;

    /**
     * Determine if the icon is custom.
     */
    protected static bool $custom = false;

    /**
     * The icon guide.
     */
    protected static IconGuide $guide;

    /** @throws Exception|InappropriateIconGuideExecution */
    public static function build(Component $component, ?string $path = null): string
    {
        InappropriateIconGuideExecution::validate($component::class);

        self::configuration();

        $type = self::$configuration->get('type');
        $style = self::$configuration->get('style');

        self::validate($type);

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (self::$custom || ! in_array($attribute, self::$guide::styles($type))) {
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
                self::$guide::get('hero', $name) !== null
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

        return $guide ?? self::$guide::get($type, $key) ?? $key;
    }

    /**
     * Get the configuration for icons and determine if it is custom.
     *
     * @throws Exception
     */
    private static function configuration(): void
    {
        self::$guide = new IconGuide;

        self::$configuration = __ts_configuration('icons');

        self::$custom = str_contains((string) self::$configuration->get('type'), 'custom:') && self::$configuration->get('custom') !== null;
    }

    /** @throws Exception */
    private static function validate(string $type): void
    {
        if (IconGuide::supported($type) || self::$custom) {
            return;
        }

        throw new Exception("The icon type [$type] is not supported.");
    }
}
