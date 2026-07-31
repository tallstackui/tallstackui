<?php

namespace TallStackUi\Support\Charts;

/**
 * Monotone cubic interpolation (Fritsch-Carlson) as cubic Beziers, so the
 * curve never leaves the range of the points it passes through.
 *
 * @internal
 */
final class Spline
{
    /** Above this, buckets collapse to their lowest and highest value. */
    public const MAX_POINTS = 120;

    /**
     * Which indexes survive downsampling. Shared across every series so the
     * curves stay aligned on the horizontal axis, and chosen by looking at
     * where any series peaks, so no spike is bucketed away.
     */
    public static function indexes(array $series, int $length): array
    {
        if ($length <= self::MAX_POINTS) {
            return range(0, max(0, $length - 1));
        }

        $size = (int) ceil($length / (self::MAX_POINTS / 2));
        $indexes = [];

        for ($start = 0; $start < $length; $start += $size) {
            $bucket = [];

            foreach ($series as $entry) {
                $slice = array_slice($entry['data'], $start, $size, true);

                if ($slice === []) {
                    continue;
                }

                // Averaging would smooth away the spikes a sparkline exists to
                // show, and a plain stride can drop the extremes the scale is
                // anchored to. Keeping both ends of every series preserves them.
                $bucket[] = (int) array_search(min($slice), $slice, true);
                $bucket[] = (int) array_search(max($slice), $slice, true);
            }

            $indexes = [...$indexes, ...array_unique($bucket)];
        }

        $indexes = array_values(array_unique($indexes));

        sort($indexes);

        return $indexes;
    }

    public static function path(array $points): string
    {
        if (($count = count($points)) < 2) {
            return '';
        }

        $slopes = self::slopes($points);
        $path = 'M'.self::coordinate($points[0]);

        for ($index = 0; $index < $count - 1; $index++) {
            $step = ($points[$index + 1][0] - $points[$index][0]) / 3;

            $first = [$points[$index][0] + $step, $points[$index][1] + $slopes[$index] * $step];
            $second = [$points[$index + 1][0] - $step, $points[$index + 1][1] - $slopes[$index + 1] * $step];

            $path .= ' C'.self::coordinate($first).' '.self::coordinate($second).' '.self::coordinate($points[$index + 1]);
        }

        return $path;
    }

    /**
     * Rounding happens here and not in path() so the tangents come from the
     * same numbers that end up in the markup. The horizontal position follows
     * the original index, keeping a downsampled series on the same time axis.
     */
    public static function points(array $values, Scale $scale, array $indexes, int $length, array $offsets = [], bool $slotted = false): array
    {
        // A single value is a constant series, the same as [7, 7, 7], so it
        // spans the plot instead of collapsing into nothing. Rendering an
        // empty card for one data point reads as a bug, not as a decision.
        if ($length === 1 && array_key_exists(0, $values)) {
            $ordinate = $scale->y($values[0] + ($offsets[0] ?? 0.0));

            return [[0.0, $ordinate], [Plot::WIDTH, $ordinate]];
        }

        // A curve owns the whole width, so its ends sit on the edges. Drawn
        // over bars it has to follow their slots, or it reads half a slot out
        // of line at either end.
        $step = match (true) {
            $slotted => $length > 0 ? Plot::WIDTH / $length : 0.0,
            $length > 1 => Plot::WIDTH / ($length - 1),
            default => 0.0,
        };

        $offset = $slotted ? $step / 2 : 0.0;
        $points = [];

        foreach ($indexes as $index) {
            if (! array_key_exists($index, $values)) {
                continue;
            }

            $points[] = [
                round($index * $step + $offset, 2),
                $scale->y($values[$index] + ($offsets[$index] ?? 0.0)),
            ];
        }

        return $points;
    }

    private static function coordinate(array $point): string
    {
        return round($point[0], 2).','.round($point[1], 2);
    }

    /**
     * Fritsch-Carlson tangents: flatten at every local extremum, then clamp
     * each pair into the circle of radius 3 so no segment can bulge past
     * either of its own two points.
     */
    private static function slopes(array $points): array
    {
        $count = count($points);
        $deltas = [];

        for ($index = 0; $index < $count - 1; $index++) {
            $deltas[$index] = ($points[$index + 1][1] - $points[$index][1]) / ($points[$index + 1][0] - $points[$index][0]);
        }

        $slopes = [0 => $deltas[0]];

        for ($index = 1; $index < $count - 1; $index++) {
            $slopes[$index] = $deltas[$index - 1] * $deltas[$index] <= 0
                ? 0.0
                : ($deltas[$index - 1] + $deltas[$index]) / 2;
        }

        $slopes[$count - 1] = $deltas[$count - 2];

        for ($index = 0; $index < $count - 1; $index++) {
            if ($deltas[$index] === 0.0) {
                $slopes[$index] = 0.0;
                $slopes[$index + 1] = 0.0;

                continue;
            }

            $alpha = $slopes[$index] / $deltas[$index];
            $beta = $slopes[$index + 1] / $deltas[$index];
            $distance = $alpha ** 2 + $beta ** 2;

            if ($distance > 9) {
                $tau = 3 / sqrt($distance);

                $slopes[$index] = $tau * $alpha * $deltas[$index];
                $slopes[$index + 1] = $tau * $beta * $deltas[$index];
            }
        }

        return $slopes;
    }
}
