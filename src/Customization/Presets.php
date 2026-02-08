<?php

namespace TallStackUi\Customization;

use InvalidArgumentException;

class Presets
{
    /** @var array<string, true|string[]|array{except: string[]}> */
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

        if ($value === true) {
            return true;
        }

        if (isset($value['except'])) {
            return ! in_array($component, $value['except'], true);
        }

        return in_array($component, $value, true);
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

    /**
     * Remove all border-radius classes from components.
     */
    public function square(array $only = [], array $except = []): self
    {
        if ($only !== [] && $except !== [] && array_intersect($only, $except) !== []) {
            throw new InvalidArgumentException('[TallStackUI] A component cannot be listed in both [only] and [except].');
        }

        if ($only !== []) {
            self::$active['square'] = $only;
        } elseif ($except !== []) {
            self::$active['square'] = ['except' => $except];
        } else {
            self::$active['square'] = true;
        }

        return $this;
    }
}
