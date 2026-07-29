<?php

namespace TallStackUi\Support\Charts;

/**
 * Square because preserveAspectRatio="none" discards the ratio anyway.
 *
 * @internal
 */
final class Plot
{
    public const HEIGHT = 100.0;

    /** A peak on y = 0 would lose half its stroke to the viewBox edge. */
    public const INSET = 4.0;

    public const WIDTH = 100.0;

    /** The drawable vertical band, between the two insets. */
    public static function band(): float
    {
        return self::bottom() - self::INSET;
    }

    public static function bottom(): float
    {
        return self::HEIGHT - self::INSET;
    }

    public static function viewbox(): string
    {
        return '0 0 '.self::WIDTH.' '.self::HEIGHT;
    }
}
