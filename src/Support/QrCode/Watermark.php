<?php

namespace TallStackUi\Support\QrCode;

final class Watermark
{
    private const GLYPH = 0.62;

    private const PITCH = 0.75;

    private const SPAN = 0.40;

    private const SQUARE = 0.26;

    private const STRIP = 0.16;

    public static function icon(int $version): array
    {
        $size = Version::size($version);
        $side = self::odd(round($size * self::SQUARE), $version);

        $knockout = self::box($size, $side, $side);
        $inner = self::inner($knockout);

        return ['knockout' => $knockout, 'x' => $inner['x'], 'y' => $inner['y'], 'size' => $inner['width']];
    }

    public static function text(int $version, int $length): array
    {
        $size = Version::size($version);
        $height = self::odd(round($size * self::STRIP), $version);
        $width = self::odd(min(round($size * self::SPAN), max($height, round($height * $length * self::PITCH))), $version);

        $knockout = self::box($size, $width, $height);
        $inner = self::inner($knockout);

        return [
            'knockout' => $knockout,
            'x' => $inner['x'] + $inner['width'] / 2,
            'y' => $inner['y'] + $inner['height'] / 2,
            'font' => round(min($inner['height'], $inner['width'] / max(1, $length * self::GLYPH)), 2),
        ];
    }

    private static function box(int $size, int $width, int $height): array
    {
        return [
            'x' => intdiv($size - $width, 2),
            'y' => intdiv($size - $height, 2),
            'width' => $width,
            'height' => $height,
        ];
    }

    private static function inner(array $knockout): array
    {
        return [
            'x' => $knockout['x'] + Path::QUIET + 0.5,
            'y' => $knockout['y'] + Path::QUIET + 0.5,
            'width' => $knockout['width'] - 1,
            'height' => $knockout['height'] - 1,
        ];
    }

    private static function odd(float $length, int $version): int
    {
        $bounded = min((int) $length, Version::size($version) - 18);

        return max(3, $bounded % 2 === 0 ? $bounded - 1 : $bounded);
    }
}
