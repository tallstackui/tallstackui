<?php

namespace TallStackUi\Support\QrCode;

/**
 * Geometry of the region a watermark takes at the centre of the grid.
 *
 * Modules are removed, not covered: there is no background to hide them
 * behind. The highest error correction level is what pays for the loss.
 */
final class Watermark
{
    /** Average glyph width relative to the font size, used to fit text into the strip. */
    private const GLYPH = 0.62;

    /** Strip width granted per character, relative to its height. */
    private const PITCH = 0.75;

    /**
     * Widest a caption strip may get. Without it the strip grows with the
     * caption until it hits the outer bound, which on a large symbol means
     * most of the width and a code no reader will take.
     */
    private const SPAN = 0.40;

    /** Share of the width a square watermark takes. */
    private const SQUARE = 0.26;

    /** Share of the width the height of a text watermark takes. */
    private const STRIP = 0.16;

    /** @return array{knockout: array, x: float, y: float, size: float} */
    public static function icon(int $version): array
    {
        $size = Version::size($version);
        $side = self::odd(round($size * self::SQUARE), $version);

        $knockout = self::box($size, $side, $side);
        $inner = self::inner($knockout);

        return ['knockout' => $knockout, 'x' => $inner['x'], 'y' => $inner['y'], 'size' => $inner['width']];
    }

    /** @return array{knockout: array, x: float, y: float, font: float} */
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

    /** The drawing area, in view box coordinates, inset half a module all round. */
    private static function inner(array $knockout): array
    {
        return [
            'x' => $knockout['x'] + Path::QUIET + 0.5,
            'y' => $knockout['y'] + Path::QUIET + 0.5,
            'width' => $knockout['width'] - 1,
            'height' => $knockout['height'] - 1,
        ];
    }

    /** Largest odd length that stays clear of rows and columns six and eight. */
    private static function odd(float $length, int $version): int
    {
        $bounded = min((int) $length, Version::size($version) - 18);

        return max(3, $bounded % 2 === 0 ? $bounded - 1 : $bounded);
    }
}
