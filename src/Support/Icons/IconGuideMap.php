<?php

namespace TallStackUi\Support\Icons;

use Exception;
use Illuminate\View\Component;
use TallStackUi\Components\Icon\Component as Icon;

class IconGuideMap
{
    /**
     * The icon configuration.
     */
    protected static array $configuration;

    /**
     * Determine if the icon is custom.
     */
    protected static bool $custom = false;

    /**
     * The icon guide.
     */
    protected static IconGuide $guide;

    /**
     * Determine if the icon type is a local SVG path.
     */
    protected static bool $local = false;

    /**
     * Build the icon.
     *
     * @throws Exception
     */
    public static function build(Component $component, ?string $path = null): string
    {
        self::configuration();

        $type = self::$configuration['type'];
        $style = self::$configuration['style'];

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (self::$custom || self::$local || ! in_array($attribute, self::$guide::styles($type))) {
                continue;
            }

            // When some attribute matches one of the keys
            // available in the supported icons styles, then
            // we want to override the style through run time.
            $style = $attribute;
        }

        $name = $component->icon ?? $component->name; // @phpstan-ignore-line

        // When using local SVG icons, we resolve the icon name from the
        // configured path, extracting the Blade component namespace from
        // it. For internal icons, the guide mapping is checked first.
        if (self::$local) {
            $namespace = str_replace(['views/components/', '/'], ['', '.'], $type);

            if (
                $component->internal && // @phpstan-ignore-line
                collect(self::$configuration['custom']['guide'])
                    ->filter()
                    ->keys()
                    ->contains($name)
            ) {
                return $namespace.'.'.self::$configuration['custom']['guide'][$name];
            }

            return $namespace.'.'.$name;
        }

        $format = fn (?string $name) => str_replace('.', '-', $name);

        // We start by checking if they are custom icons, if the use is internal (called internally by TSUI).
        // If these requirements are met, we use an algorithm that will filter the icon map to remove nulls
        // then invert the key to value and finally check if the icon exists in the icon map. If it does,
        // then it is a custom icon mapped by the configuration.
        if (
            self::$custom &&
            $component->internal && // @phpstan-ignore-line
            collect(self::$configuration['custom']['guide'])
                ->filter()
                ->keys()
                ->contains($name)
        ) {
            return $format(self::$configuration['custom']['guide'][$format($name)]);
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

        if (self::$custom || self::$local) {
            return $key;
        }

        return self::$configuration['custom']['guide'][$key] ?? self::$guide::get('heroicons', $key) ?? $key;
    }

    /**
     * Get the configuration for icons and determine if it is custom.
     */
    private static function configuration(): void
    {
        self::$guide = new IconGuide;

        self::$configuration = __ts_get_component_configuration(Icon::class);

        $type = (string) self::$configuration['type'];

        self::$custom = str_contains($type, '/blade-') && self::$configuration['custom'] !== null;
        self::$local = ! in_array($type, ['heroicons', 'hero']) && ! self::$custom;
    }
}
