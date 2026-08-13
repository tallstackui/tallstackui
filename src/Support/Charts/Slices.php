<?php

namespace TallStackUi\Support\Charts;

/** @internal */
final class Slices
{
    public const CENTER = 50.0;

    public const HOLE = 0.58;

    public const RADIUS = 46.0;

    public static function of(array $values, bool $donut = false): array
    {
        $values = array_map(static fn (float $value): float => max(0.0, $value), $values);
        $total = array_sum($values);

        if ($total <= 0.0) {
            return [];
        }

        $inner = $donut ? self::RADIUS * self::HOLE : 0.0;
        $angle = -M_PI / 2;
        $slices = [];

        foreach ($values as $index => $value) {
            $sweep = ($value / $total) * 2 * M_PI;

            if ($sweep <= 0.0) {
                continue;
            }

            $slices[] = [
                'path' => self::path($angle, $angle + $sweep, $inner),
                'percentage' => round($value / $total * 100, 2),
                'value' => $value,
                'index' => $index,
            ];

            $angle += $sweep;
        }

        return $slices;
    }

    private static function coordinate(float $angle, float $radius): string
    {
        return round(self::CENTER + cos($angle) * $radius, 2).','.round(self::CENTER + sin($angle) * $radius, 2);
    }

    private static function path(float $from, float $to, float $inner): string
    {
        // A single arc command cannot express a full turn, because its start
        // and end points would coincide. Splitting it in half is the standard
        // way out, and it costs nothing on the ordinary case.
        if ($to - $from >= 2 * M_PI - 1e-9) {
            return self::ring($from, $inner);
        }

        $large = ($to - $from) > M_PI ? 1 : 0;

        $path = 'M'.self::coordinate($from, self::RADIUS)
            .' A'.self::RADIUS.','.self::RADIUS.' 0 '.$large.',1 '.self::coordinate($to, self::RADIUS);

        if ($inner <= 0.0) {
            return $path.' L'.self::CENTER.','.self::CENTER.' Z';
        }

        return $path
            .' L'.self::coordinate($to, $inner)
            .' A'.$inner.','.$inner.' 0 '.$large.',0 '.self::coordinate($from, $inner)
            .' Z';
    }

    private static function ring(float $from, float $inner): string
    {
        $half = $from + M_PI;

        $path = 'M'.self::coordinate($from, self::RADIUS)
            .' A'.self::RADIUS.','.self::RADIUS.' 0 1,1 '.self::coordinate($half, self::RADIUS)
            .' A'.self::RADIUS.','.self::RADIUS.' 0 1,1 '.self::coordinate($from, self::RADIUS);

        if ($inner <= 0.0) {
            return $path.' Z';
        }

        return $path
            .' M'.self::coordinate($from, $inner)
            .' A'.$inner.','.$inner.' 0 1,0 '.self::coordinate($half, $inner)
            .' A'.$inner.','.$inner.' 0 1,0 '.self::coordinate($from, $inner)
            .' Z';
    }
}
