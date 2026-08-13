<?php

namespace TallStackUi\Support\Charts;

/** @internal */
final class Plot
{
    public const HEIGHT = 100.0;

    public const INSET = 4.0;

    public const WIDTH = 100.0;

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
