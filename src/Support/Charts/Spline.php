<?php

namespace TallStackUi\Support\Charts;

/** @internal */
final class Spline
{
    public const CURVES = ['smooth', 'straight', 'step'];

    public const MAX_POINTS = 120;

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
                $slice = array_filter(
                    array_slice($entry['data'], $start, $size, true),
                    static fn (?float $value): bool => $value !== null
                );

                if ($slice === []) {
                    continue;
                }

                // Min and max per bucket, so no spike or scale anchor is lost.
                $bucket[] = (int) array_search(min($slice), $slice, true);
                $bucket[] = (int) array_search(max($slice), $slice, true);
            }

            $indexes = [...$indexes, ...array_unique($bucket)];
        }

        $indexes = array_values(array_unique($indexes));

        sort($indexes);

        return $indexes;
    }

    public static function path(array $points, string $curve = 'smooth'): string
    {
        return implode(' ', array_filter(array_map(
            static fn (array $run): string => self::segment($run, $curve),
            self::runs($points)
        )));
    }

    /** A null stays in the list as a gap; an index the bucketing left out is not one. */
    public static function points(array $values, Scale $scale, array $indexes, int $length, array $offsets = [], bool $slotted = false): array
    {
        // A single value spans the plot as a constant series.
        if ($length === 1 && isset($values[0])) {
            $ordinate = $scale->y($values[0] + ($offsets[0] ?? 0.0));

            return [[0.0, $ordinate], [Plot::WIDTH, $ordinate]];
        }

        // A curve ends on the edges; over bars it follows their slots.
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

            if ($values[$index] === null) {
                $points[] = null;

                continue;
            }

            $points[] = [
                round($index * $step + $offset, 2),
                $scale->y($values[$index] + ($offsets[$index] ?? 0.0)),
            ];
        }

        return $points;
    }

    public static function runs(array $points): array
    {
        $runs = [];
        $run = [];

        foreach ($points as $point) {
            if ($point === null) {
                if ($run !== []) {
                    $runs[] = $run;
                }

                $run = [];

                continue;
            }

            $run[] = $point;
        }

        if ($run !== []) {
            $runs[] = $run;
        }

        return $runs;
    }

    public static function segment(array $points, string $curve = 'smooth', bool $reversed = false): string
    {
        if (($count = count($points)) < 2) {
            return '';
        }

        $path = 'M'.self::coordinate($points[0]);

        if ($curve === 'straight') {
            for ($index = 1; $index < $count; $index++) {
                $path .= ' L'.self::coordinate($points[$index]);
            }

            return $path;
        }

        if ($curve === 'step') {
            for ($index = 1; $index < $count; $index++) {
                $x = round($points[$index][0], 2);
                $y = round($points[$index][1], 2);

                // Walked backwards, a step drops first to trace the same corners.
                $path .= $reversed ? ' V'.$y.' H'.$x : ' H'.$x.' V'.$y;
            }

            return $path;
        }

        $slopes = self::slopes($points);

        for ($index = 0; $index < $count - 1; $index++) {
            $step = ($points[$index + 1][0] - $points[$index][0]) / 3;

            $first = [$points[$index][0] + $step, $points[$index][1] + $slopes[$index] * $step];
            $second = [$points[$index + 1][0] - $step, $points[$index + 1][1] - $slopes[$index + 1] * $step];

            $path .= ' C'.self::coordinate($first).' '.self::coordinate($second).' '.self::coordinate($points[$index + 1]);
        }

        return $path;
    }

    private static function coordinate(array $point): string
    {
        return round($point[0], 2).','.round($point[1], 2);
    }

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
