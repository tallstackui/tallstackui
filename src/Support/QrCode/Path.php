<?php

namespace TallStackUi\Support\QrCode;

final class Path
{
    public const QUIET = 4;

    public static function of(Matrix $matrix, array $knockout = []): string
    {
        $rows = $matrix->toArray();
        $path = '';

        foreach ($rows as $y => $row) {
            $start = null;

            foreach ([...$row, false] as $x => $dark) {
                $paint = $dark && ! self::inside($knockout, $x, $y);

                if ($paint) {
                    $start ??= $x;

                    continue;
                }

                if ($start !== null) {
                    $path .= self::run($start, $y, $x - $start);
                    $start = null;
                }
            }
        }

        return $path;
    }

    public static function viewbox(Matrix $matrix): string
    {
        return '0 0 '.self::span($matrix).' '.self::span($matrix);
    }

    private static function inside(array $knockout, int $x, int $y): bool
    {
        if ($knockout === []) {
            return false;
        }

        return $x >= $knockout['x']
            && $x < $knockout['x'] + $knockout['width']
            && $y >= $knockout['y']
            && $y < $knockout['y'] + $knockout['height'];
    }

    private static function run(int $x, int $y, int $length): string
    {
        return 'M'.($x + self::QUIET).' '.($y + self::QUIET).'h'.$length.'v1h-'.$length.'z';
    }

    private static function span(Matrix $matrix): int
    {
        return $matrix->size() + self::QUIET * 2;
    }
}
