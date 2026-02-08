<?php

namespace TallStackUi\Customization;

class Presets
{
    /** @var array<string, true|string[]> */
    private static array $active = [];

    /**
     * Determine whether a preset is active for the given component.
     */
    public static function is(string $preset, string $component): bool
    {
        if (! isset(self::$active[$preset])) {
            return false;
        }

        $value = self::$active[$preset];

        return $value === true || in_array($component, $value, true);
    }

    /**
     * Clear all registered presets.
     */
    public static function reset(): void
    {
        self::$active = [];
    }

    /**
     * Remove transition/animation directives from components.
     */
    public function flash(string ...$components): self
    {
        self::$active['flash'] = $components === [] ? true : $components;

        return $this;
    }
}
