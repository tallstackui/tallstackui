<?php

namespace TallStackUi\Support\Charts;

/**
 * @internal
 */
final class Bars
{
    /** Share of a slot left empty, so neighbouring categories stay apart. */
    public const GUTTER = 0.3;

    public static function of(array $series, array $scales, int $length): array
    {
        if ($series === [] || $length === 0) {
            return [];
        }

        $slot = Plot::WIDTH / $length;
        $band = $slot * (1 - self::GUTTER);
        $width = $band / count($series);

        $bars = [];

        foreach ($series as $position => $entry) {
            $scale = $scales[$position];
            $baseline = $scale->zero();
            $row = [];

            for ($index = 0; $index < $length; $index++) {
                if (! array_key_exists($index, $entry['data'])) {
                    continue;
                }

                $y = $scale->y($entry['data'][$index]);

                $row[] = [
                    'x' => round($index * $slot + ($slot - $band) / 2 + $position * $width, 2),
                    'y' => round(min($y, $baseline), 2),
                    'width' => round($width, 2),
                    // Zero-height rectangles are invisible in SVG; a hairline
                    // keeps an empty category from disappearing entirely.
                    'height' => round(max(abs($baseline - $y), 0.4), 2),
                    'index' => $index,
                ];
            }

            $bars[] = $row;
        }

        return $bars;
    }

    public static function offsets(array $series, int $length): array
    {
        $offsets = [];
        $running = array_fill(0, max(1, $length), 0.0);

        foreach ($series as $entry) {
            $offsets[] = $running;

            for ($index = 0; $index < $length; $index++) {
                $running[$index] += $entry['data'][$index] ?? 0.0;
            }
        }

        return $offsets;
    }
}
