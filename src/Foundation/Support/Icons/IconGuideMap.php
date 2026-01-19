<?php

namespace TallStackUi\Foundation\Support\Icons;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

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

    /**
     * Build the icon.
     *
     * @throws Exception
     */
    public static function build(Component $component, ?string $path = null): string
    {
        self::configuration();

        $type = self::$configuration->get('type');
        $style = self::$configuration->get('style');

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (self::$custom || ! in_array($attribute, self::$guide::styles($type))) {
                continue;
            }

            // When some attribute matches one of the keys
            // available in the supported icons styles, then
            // we want to override the style through run time.
            $style = $attribute;
        }

        $name = $component->icon ?? $component->name; // @phpstan-ignore-line

        $format = fn (?string $name) => str_replace('.', '-', $name);

        // We start by checking if they are custom icons, if the use is internal (called internally by TSUI).
        // If these requirements are met, we use an algorithm that will filter the icon map to remove nulls
        // then invert the key to value and finally check if the icon exists in the icon map. If it does,
        // then it is a custom icon mapped by the configuration.
        if (
            self::$custom &&
            $component->internal && // @phpstan-ignore-line
            collect(self::$configuration->get('custom')['guide'])
                ->filter()
                ->keys()
                ->contains($name)
        ) {
            return $format(self::$configuration->get('custom')['guide'][$format($name)]);
            // Otherwise, if it is customized and not internal, then it is a custom icon
            // that is not mapped, for manual use purposes, so the dot sign is strategic.
        } elseif (self::$custom && str_contains($name, '.')) {
            return $format($name);
        }

        $component = sprintf('heroicons.%s.%s', $style, $name);

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

        if (self::$custom) {
            return $key;
        }

        return self::$configuration->get('custom')['guide'][$key] ?? self::$guide::get('heroicons', $key) ?? $key;
    }

    /**
     * Get the configuration for icons and determine if it is custom.
     */
    private static function configuration(): void
    {
        self::$guide = new IconGuide;

        self::$configuration = collect(config('tallstackui.icons'));

        self::$custom = str_contains((string) self::$configuration->get('type'), '/blade-') && self::$configuration->get('custom') !== null;
    }
}
