<?php

namespace TallStackUi\Support\Charts;

/**
 * The internal coordinate space every chart type draws into. It is square
 * because preserveAspectRatio="none" discards the ratio anyway, which leaves
 * one user unit reading as exactly one percent of the rendered box.
 *
 * @internal
 */
final class Plot
{
    public const HEIGHT = 100.0;

    /**
     * Vertical breathing room. The stroke is centred on the path, so a peak
     * sitting on y = 0 would lose half its width to the viewBox edge.
     */
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
