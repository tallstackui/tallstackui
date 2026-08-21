<?php

namespace TallStackUi\Support\Charts;

/** @internal */
final class Scale
{
    public const TICKS = 5;

    private function __construct(
        public readonly float $min,
        public readonly float $max,
        public readonly bool $flat,
    ) {
        //
    }

    public static function of(array $values, bool $nice = false, bool $zero = false, int $ticks = self::TICKS): self
    {
        if ($values === []) {
            return new self(0.0, 0.0, true);
        }

        $min = min($values);
        $max = max($values);

        if ($zero) {
            $min = min($min, 0.0);
            $max = max($max, 0.0);
        }

        // A relative epsilon: normalizing against float noise would blow it up into a zigzag.
        if ($max - $min <= max(abs($min), abs($max)) * 1e-12) {
            return new self($min, $max, true);
        }

        if ($nice) {
            // Pinned to an exact tick count so both axes share the same rows.
            $step = self::step($max - $min, $ticks);
            $bottom = floor($min / $step) * $step;

            while ($bottom + $step * ($ticks - 1) < $max) {
                $step = self::step($step * $ticks, $ticks);
                $bottom = floor($min / $step) * $step;
            }

            $min = $bottom;
            $max = $bottom + $step * ($ticks - 1);
        }

        return new self($min, $max, false);
    }

    private static function step(float $range, int $count = 5): float
    {
        $raw = $range / max(1, $count - 1);
        $magnitude = 10 ** floor(log10($raw));
        $normalized = $raw / $magnitude;

        $step = match (true) {
            $normalized <= 1.0 => 1.0,
            $normalized <= 2.0 => 2.0,
            $normalized <= 2.5 => 2.5,
            $normalized <= 5.0 => 5.0,
            default => 10.0,
        };

        return $step * $magnitude;
    }

    public function ticks(int $count = self::TICKS): array
    {
        if ($this->flat) {
            return [$this->min];
        }

        // Divided, not re-derived: a recomputed step could disagree by one row.
        $step = ($this->max - $this->min) / ($count - 1);

        return array_map(
            fn (int $index): float => round($this->min + $step * $index, 10),
            range(0, $count - 1)
        );
    }

    public function y(float $value): float
    {
        if ($this->flat) {
            return Plot::INSET + Plot::band() / 2;
        }

        return round(Plot::bottom() - (($value - $this->min) / ($this->max - $this->min)) * Plot::band(), 2);
    }

    public function zero(): float
    {
        return $this->y(max($this->min, min($this->max, 0.0)));
    }
}
