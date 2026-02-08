<?php

namespace TallStackUi\Customization;

/**
 * Presets provide predefined customization recipes that can be
 * applied globally or to specific components at boot time.
 *
 * Presets use static state to achieve O(1) lookups at render
 * time — no reflection, no container resolution, no array
 * merging. Register presets once in a service provider and
 * every component render benefits with negligible overhead.
 *
 * Usage:
 *   // All components — removes every x-transition directive
 *   TallStackUi::customize()->presets()->flash();
 *
 *   // Specific components only
 *   TallStackUi::customize()->presets()->flash(
 *       \TallStackUi\Components\Modal\Component::class,
 *       \TallStackUi\Components\Slide\Component::class,
 *   );
 *
 * @see \TallStackUi\Customization\Customization::presets()
 */
class Presets
{
    /** @var array<string, true|string[]> */
    private static array $active = [];

    /**
     * Determine whether a preset is active for the given component.
     *
     * Returns true when the preset was registered globally (no
     * component filter) or when the component's class appears in
     * the filtered list.
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
     *
     * Primarily used in tests to guarantee isolation between
     * test cases. Should not be called in production code.
     */
    public static function reset(): void
    {
        self::$active = [];
    }

    /**
     * Remove transition/animation directives from components.
     *
     * When called without arguments every component that checks
     * for this preset will strip its x-transition attributes.
     * Pass one or more fully-qualified component class names to
     * limit the effect to those components only.
     */
    public function flash(string ...$components): self
    {
        self::$active['flash'] = $components === [] ? true : $components;

        return $this;
    }
}
