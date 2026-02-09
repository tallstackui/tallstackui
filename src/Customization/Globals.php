<?php

namespace TallStackUi\Customization;

use InvalidArgumentException;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Toast\Component as Toast;

class Globals
{
    /** @var array<string, true|string[]|array{except: string[]}> */
    private static array $active = [];

    /**
     * Determine whether a global is active for the given component.
     */
    public static function is(string $global, string $component): bool
    {
        if (! isset(self::$active[$global])) {
            return false;
        }

        $value = self::$active[$global];

        if ($value === true) {
            return true;
        }

        if (isset($value['except'])) {
            return ! in_array($component, $value['except'], true);
        }

        return in_array($component, $value, true);
    }

    /**
     * Clear all registered globals.
     */
    public static function reset(): void
    {
        self::$active = [];
    }

    /**
     * Invert component (Dialog & Toast) colors, making the body take the type color.
     */
    public function colorful(bool $toast = true, bool $dialog = true): self
    {
        if ($toast) {
            self::$active['colorful'][] = Toast::class;
        }

        if ($dialog) {
            self::$active['colorful'][] = Dialog::class;
        }

        return $this;
    }

    /**
     * Remove transition/animation directives from components.
     */
    public function flash(array $only = [], array $except = []): self
    {
        if ($only !== [] && $except !== [] && array_intersect($only, $except) !== []) {
            throw new InvalidArgumentException('[TallStackUI] A component cannot be listed in both [only] and [except].');
        }

        if ($only !== []) {
            self::$active['flash'] = $only;
        } elseif ($except !== []) {
            self::$active['flash'] = ['except' => $except];
        } else {
            self::$active['flash'] = true;
        }

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
