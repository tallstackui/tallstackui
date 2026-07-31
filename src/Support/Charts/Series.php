<?php

namespace TallStackUi\Support\Charts;

use Illuminate\Support\Collection;

/**
 * @internal
 */
final class Series
{
    public const AXES = ['left', 'right'];

    public const TYPES = ['area', 'line', 'bar'];

    public static function length(array $series): int
    {
        return array_reduce($series, static fn (int $carry, array $entry): int => max($carry, count($entry['data'])), 0);
    }

    public static function normalize(Collection|array|null $series): array
    {
        if ($series instanceof Collection) {
            $series = $series->all();
        }

        if ($series === null || $series === []) {
            return [];
        }

        if (! self::grouped($series)) {
            return [['name' => null, 'data' => self::floats($series), 'axis' => 'left', 'type' => null]];
        }

        $normalized = [];

        foreach ($series as $entry) {
            $entry = $entry instanceof Collection ? $entry->all() : $entry;
            $data = $entry['data'] ?? [];

            $normalized[] = [
                'name' => isset($entry['name']) ? (string) $entry['name'] : null,
                'data' => self::floats($data instanceof Collection ? $data->all() : (array) $data),
                'axis' => $entry['axis'] ?? 'left',
                // Left null because the chart type is only known to the runtime.
                'type' => $entry['type'] ?? null,
            ];
        }

        return $normalized;
    }

    public static function on(array $series, string $axis): array
    {
        return array_values(array_filter($series, static fn (array $entry): bool => $entry['axis'] === $axis));
    }

    public static function overrides(array $series): array
    {
        return array_values(array_filter(array_column($series, 'type')));
    }

    /** A single bar is enough to divide the horizontal axis into slots, curves included. */
    public static function slotted(array $series, string $type): bool
    {
        foreach ($series as $entry) {
            if (($entry['type'] ?? $type) === 'bar') {
                return true;
            }
        }

        return false;
    }

    public static function values(array $series): array
    {
        return $series === [] ? [] : array_merge(...array_column($series, 'data'));
    }

    /**
     * NAN and INF pass is_numeric() and would poison min()/max() into a path
     * of "NAN,NAN", which renders as nothing at all.
     */
    public static function violation(Collection|array|null $series): ?string
    {
        if ($series instanceof Collection) {
            $series = $series->all();
        }

        if ($series === null) {
            return 'The [series] attribute is required.';
        }

        if ($series !== [] && self::grouped($series)) {
            foreach ($series as $entry) {
                $entry = $entry instanceof Collection ? $entry->all() : $entry;

                if (! is_array($entry) || ! array_key_exists('data', $entry)) {
                    return 'Every entry of [series] must carry a [data] key.';
                }

                $data = $entry['data'] instanceof Collection ? $entry['data']->all() : $entry['data'];

                if (! is_array($data)) {
                    return 'The [data] of every series must be an array or a collection.';
                }

                if (isset($entry['axis']) && ! in_array($entry['axis'], self::AXES, true)) {
                    return 'The [axis] of every series must be one of: '.implode(', ', self::AXES).'.';
                }

                if (isset($entry['type']) && ! in_array($entry['type'], self::TYPES, true)) {
                    return 'The [type] of every series must be one of: '.implode(', ', self::TYPES).'.';
                }

                if ($violation = self::numeric($data)) {
                    return $violation;
                }
            }

            return null;
        }

        return self::numeric($series);
    }

    private static function floats(array $values): array
    {
        return array_values(array_map(static fn (mixed $value): float => (float) $value, $values));
    }

    /**
     * Tells the grouped shape from the flat one by looking at the first entry:
     * a series row is an array or a collection, a plotted value never is.
     */
    private static function grouped(array $series): bool
    {
        $first = reset($series);

        return is_array($first) || $first instanceof Collection;
    }

    private static function numeric(array $values): ?string
    {
        foreach ($values as $value) {
            if (! is_numeric($value) || ! is_finite((float) $value)) {
                return 'The [series] must contain only numeric values.';
            }
        }

        return null;
    }
}
