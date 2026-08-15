<?php

namespace TallStackUi\Support\Miscellaneous;

use Exception;
use TallStackUi\Components\Editor\Component as Editor;

/** @internal */
class EditorOutputClasses
{
    /**
     * Resolve the class stamped on each element the Editor writes.
     *
     * @throws Exception
     */
    public static function of(Editor $component, string $prefix): array
    {
        self::validate($component, $prefix);

        if ($component->markdown || $component->outputClasses === false) {
            return [];
        }

        $defaults = array_map(fn (string $name): string => $prefix.$name, Editor::OUTPUT_CLASSES);

        $classes = is_array($component->outputClasses)
            ? array_merge($defaults, $component->outputClasses)
            : $defaults;

        $invalid = array_filter($classes, fn (mixed $class): bool => ! is_string($class) || ! str_starts_with($class, $prefix));

        if ($invalid !== []) {
            __ts_validation_exception($component, sprintf(
                'The output class of [%s] must start with [%s].',
                implode(', ', array_keys($invalid)),
                $prefix,
            ));
        }

        return $classes;
    }

    /**
     * The prefix is the whole of what the sanitizer lets through, so an empty
     * or loose one would turn the class attribute into an open door.
     *
     * @throws Exception
     */
    private static function validate(Editor $component, string $prefix): void
    {
        if (preg_match(Editor::OUTPUT_CLASSES_PREFIX_FORMAT, $prefix) === 1) {
            return;
        }

        __ts_validation_exception($component, sprintf('The output classes prefix [%s] must be lowercase, dash separated and end with a dash.', $prefix));
    }
}
