<?php

namespace TallStackUi\Support\Charts;

/** @internal */
final class Bars
{
    public const GUTTER = 0.3;

    public const RADIUS = 0.6;

    public static function corners(array $series, int $length, array $groups = []): array
    {
        $first = [];
        $last = [];

        foreach ($series as $position => $entry) {
            foreach (self::keys($entry, $groups[$position] ?? '', $length) as $index => $key) {
                $first[$key] ??= $position;
                $last[$key] = $position;
            }
        }

        $corners = [];

        foreach ($series as $position => $entry) {
            foreach (self::keys($entry, $groups[$position] ?? '', $length) as $index => $key) {
                $negative = $entry['data'][$index] < 0;
                $facing = ($groups[$position] ?? '').'|'.($negative ? '+' : '-').'|'.$index;

                $outer = $position === $last[$key];
                // The end that meets zero is only an end while the column stops
                // there. Carrying on past the axis makes it a seam like any
                // other, and rounding both of its sides opens the same gap.
                $inner = $position === $first[$key] && ! isset($first[$facing]);

                $corners[$position][$index] = $negative
                    ? ['head' => $inner, 'foot' => $outer]
                    : ['head' => $outer, 'foot' => $inner];
            }
        }

        return $corners;
    }

    public static function of(array $series, array $scales, int $length): array
    {
        if ($series === [] || $length === 0) {
            return [];
        }

        $slot = Plot::WIDTH / $length;
        $band = $slot * (1 - self::GUTTER);
        $width = $band / count($series);

        $bars = [];
        $share = 0;

        foreach ($series as $position => $entry) {
            $scale = $scales[$position];
            $baseline = $scale->zero();
            $row = [];

            for ($index = 0; $index < $length; $index++) {
                if (! array_key_exists($index, $entry['data'])) {
                    continue;
                }

                $y = $scale->y($entry['data'][$index]);

                $bar = [
                    'x' => round($index * $slot + ($slot - $band) / 2 + $share * $width, 2),
                    'y' => round(min($y, $baseline), 2),
                    'width' => round($width, 2),
                    // Zero-height rectangles are invisible in SVG; a hairline
                    // keeps an empty category from disappearing entirely.
                    'height' => round(max(abs($baseline - $y), 0.4), 2),
                    'index' => $index,
                ];

                $row[] = [...$bar, 'path' => self::path($bar)];
            }

            $bars[$position] = $row;
            $share++;
        }

        return $bars;
    }

    public static function offsets(array $series, int $length, array $groups = []): array
    {
        $offsets = [];
        $running = [];

        foreach ($series as $position => $entry) {
            $group = $groups[$position] ?? '';
            $running[$group] ??= self::running($length);
            $row = [];

            for ($index = 0; $index < $length; $index++) {
                $value = $entry['data'][$index] ?? 0.0;
                $sign = $value < 0 ? '-' : '+';

                $row[$index] = $running[$group][$sign][$index];
                $running[$group][$sign][$index] += $value;
            }

            $offsets[] = $row;
        }

        return $offsets;
    }

    public static function path(array $bar, bool $top = true, bool $bottom = true): string
    {
        $left = $bar['x'];
        $upper = $bar['y'];
        $right = round($left + $bar['width'], 2);
        $lower = round($upper + $bar['height'], 2);

        // Clamped, or the corners of a hairline bar fold through each other.
        $radius = round(min(self::RADIUS, $bar['width'] / 2, $bar['height'] / 2), 2);

        $head = $top ? $radius : 0.0;
        $foot = $bottom ? $radius : 0.0;

        $arc = static fn (float $x, float $y): string => 'A'.$radius.','.$radius.' 0 0,1 '.round($x, 2).','.round($y, 2);

        return implode(' ', array_filter([
            'M'.$left.','.round($upper + $head, 2),
            $head > 0 ? $arc($left + $radius, $upper) : null,
            'H'.round($right - $head, 2),
            $head > 0 ? $arc($right, $upper + $radius) : null,
            'V'.round($lower - $foot, 2),
            $foot > 0 ? $arc($right - $radius, $lower) : null,
            'H'.round($left + $foot, 2),
            $foot > 0 ? $arc($left, $lower - $radius) : null,
            'Z',
        ]));
    }

    public static function totals(array $series, int $length, array $groups = []): array
    {
        $totals = [];

        foreach ($series as $position => $entry) {
            $group = $groups[$position] ?? '';
            $totals[$group] ??= self::running($length);

            for ($index = 0; $index < $length; $index++) {
                $value = $entry['data'][$index] ?? 0.0;

                $totals[$group][$value < 0 ? '-' : '+'][$index] += $value;
            }
        }

        $reached = [];

        foreach ($totals as $group) {
            $reached = [...$reached, ...$group['+'], ...$group['-']];
        }

        return $reached;
    }

    private static function keys(array $entry, string $group, int $length): array
    {
        $keys = [];

        for ($index = 0; $index < $length; $index++) {
            // Zero renders as a hairline so the category does not vanish, but
            // it is not what ends a column. Counted, it would take the rounding
            // onto a sliver and leave the visible end of the stack square.
            if (! array_key_exists($index, $entry['data']) || $entry['data'][$index] === 0.0) {
                continue;
            }

            $keys[$index] = $group.'|'.($entry['data'][$index] < 0 ? '-' : '+').'|'.$index;
        }

        return $keys;
    }

    private static function running(int $length): array
    {
        $blank = array_fill(0, max(1, $length), 0.0);

        return ['+' => $blank, '-' => $blank];
    }
}
