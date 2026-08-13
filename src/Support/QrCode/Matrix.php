<?php

namespace TallStackUi\Support\QrCode;

final class Matrix
{
    /** Generator of the BCH code protecting the format information. */
    private const FORMAT_GENERATOR = 0x537;

    /** Constant the format information is scrambled with, so it never reads as all zeroes. */
    private const FORMAT_MASK = 0x5412;

    /** Generator of the BCH code protecting the version information. */
    private const VERSION_GENERATOR = 0x1F25;

    private array $modules = [];

    private array $reserved = [];

    public function __construct(public readonly int $version, public readonly string $level)
    {
        $size = $this->size();

        $this->modules = array_fill(0, $size, array_fill(0, $size, false));
        $this->reserved = $this->modules;
    }

    public function build(array $codewords): self
    {
        $this->patterns();
        $this->place($codewords);

        $mask = $this->elected();

        $this->format($mask);
        $this->apply($mask);

        return $this;
    }

    public function size(): int
    {
        return Version::size($this->version);
    }

    public function toArray(): array
    {
        return $this->modules;
    }

    private function alignment(int $x, int $y): void
    {
        for ($row = -2; $row <= 2; $row++) {
            for ($column = -2; $column <= 2; $column++) {
                $this->reserve($x + $column, $y + $row, max(abs($row), abs($column)) !== 1);
            }
        }
    }

    /** An involution: the same call applies and undoes it. Skips function patterns. */
    private function apply(int $mask): void
    {
        $size = $this->size();

        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                if (! $this->reserved[$y][$x] && $this->masked($mask, $x, $y)) {
                    $this->modules[$y][$x] = ! $this->modules[$y][$x];
                }
            }
        }
    }

    private function bch(int $value, int $generator, int $high, int $low): int
    {
        for ($index = $high; $index >= $low; $index--) {
            if (($value >> $index) & 1) {
                $value ^= $generator << ($index - $low);
            }
        }

        return $value;
    }

    private function elected(): int
    {
        $best = 0;
        $lowest = PHP_INT_MAX;

        for ($mask = 0; $mask < 8; $mask++) {
            $this->format($mask);
            $this->apply($mask);

            $penalty = $this->penalty();

            $this->apply($mask);

            if ($penalty < $lowest) {
                $lowest = $penalty;
                $best = $mask;
            }
        }

        return $best;
    }

    private function finder(int $x, int $y): void
    {
        for ($row = -1; $row <= 7; $row++) {
            for ($column = -1; $column <= 7; $column++) {
                $inside = $row >= 0 && $row <= 6 && $column >= 0 && $column <= 6;

                $this->reserve($x + $column, $y + $row, $inside && max(abs($row - 3), abs($column - 3)) !== 2);
            }
        }
    }

    private function finders(string $line): int
    {
        $line = '0000'.$line.'0000';
        $length = strlen($line);
        $found = 0;

        for ($index = 0; $index + 11 <= $length; $index++) {
            $window = substr($line, $index, 11);

            if ($window === '10111010000' || $window === '00001011101') {
                $found++;
            }
        }

        return $found;
    }

    private function format(int $mask): void
    {
        $size = $this->size();
        $data = (Version::indicator($this->level) << 3) | $mask;
        $bits = (($data << 10) | $this->bch($data << 10, self::FORMAT_GENERATOR, 14, 10)) ^ self::FORMAT_MASK;

        for ($index = 0; $index < 15; $index++) {
            $bit = (bool) (($bits >> $index) & 1);

            // First copy, wrapping the top left finder. Column eight going
            // down, then row eight coming back out.
            match (true) {
                $index < 6 => $this->modules[$index][8] = $bit,
                $index === 6 => $this->modules[7][8] = $bit,
                $index === 7 => $this->modules[8][8] = $bit,
                $index === 8 => $this->modules[8][7] = $bit,
                default => $this->modules[8][14 - $index] = $bit,
            };

            // Second copy, split between the other two finders.
            if ($index < 8) {
                $this->modules[8][$size - 1 - $index] = $bit;
            } else {
                $this->modules[$size - 15 + $index][8] = $bit;
            }
        }
    }

    private function information(): void
    {
        $size = $this->size();
        $bits = ($this->version << 12) | $this->bch($this->version << 12, self::VERSION_GENERATOR, 17, 12);

        for ($index = 0; $index < 18; $index++) {
            $bit = (bool) (($bits >> $index) & 1);
            $row = intdiv($index, 3);
            $column = $index % 3;

            $this->reserve($size - 11 + $column, $row, $bit);
            $this->reserve($row, $size - 11 + $column, $bit);
        }
    }

    private function masked(int $mask, int $x, int $y): bool
    {
        return match ($mask) {
            0 => ($y + $x) % 2 === 0,
            1 => $y % 2 === 0,
            2 => $x % 3 === 0,
            3 => ($y + $x) % 3 === 0,
            4 => (intdiv($y, 2) + intdiv($x, 3)) % 2 === 0,
            5 => ($y * $x) % 2 + ($y * $x) % 3 === 0,
            6 => (($y * $x) % 2 + ($y * $x) % 3) % 2 === 0,
            default => (($y + $x) % 2 + ($y * $x) % 3) % 2 === 0,
        };
    }

    private function patterns(): void
    {
        $size = $this->size();

        foreach ([[0, 0], [$size - 7, 0], [0, $size - 7]] as [$x, $y]) {
            $this->finder($x, $y);
        }

        for ($index = 8; $index < $size - 8; $index++) {
            $this->reserve($index, 6, $index % 2 === 0);
            $this->reserve(6, $index, $index % 2 === 0);
        }

        foreach (Version::alignments($this->version) as [$x, $y]) {
            $this->alignment($x, $y);
        }

        // Reserved light for now: format() writes the real values once the
        // mask is known. Index six is skipped on both axes because the timing
        // patterns already own it.
        for ($index = 0; $index <= 8; $index++) {
            if ($index === 6) {
                continue;
            }

            $this->reserve(8, $index, false);
            $this->reserve($index, 8, false);
        }

        for ($index = 0; $index < 8; $index++) {
            $this->reserve(8, $size - 1 - $index, false);
            $this->reserve($size - 1 - $index, 8, false);
        }

        // Always dark, and the only module of the format information block
        // that carries nothing. Set last so the loop above cannot clear it.
        $this->reserve(8, $size - 8, true);

        if ($this->version >= 7) {
            $this->information();
        }
    }

    private function penalty(): int
    {
        $size = $this->size();
        $total = 0;
        $dark = 0;

        // The first and third rules both read whole lines, so each axis is
        // walked once and the two scores accumulate together.
        foreach ([true, false] as $horizontal) {
            for ($outer = 0; $outer < $size; $outer++) {
                $run = 0;
                $previous = null;
                $line = '';

                for ($inner = 0; $inner < $size; $inner++) {
                    $module = $horizontal ? $this->modules[$outer][$inner] : $this->modules[$inner][$outer];

                    $line .= $module ? '1' : '0';

                    if ($module === $previous) {
                        $run++;

                        $total += $run === 5 ? 3 : ($run > 5 ? 1 : 0);
                    } else {
                        $run = 1;
                        $previous = $module;
                    }

                    if ($horizontal && $module) {
                        $dark++;
                    }
                }

                $total += 40 * $this->finders($line);
            }
        }

        for ($y = 0; $y < $size - 1; $y++) {
            for ($x = 0; $x < $size - 1; $x++) {
                $module = $this->modules[$y][$x];

                if ($module === $this->modules[$y][$x + 1]
                    && $module === $this->modules[$y + 1][$x]
                    && $module === $this->modules[$y + 1][$x + 1]) {
                    $total += 3;
                }
            }
        }

        $ratio = $dark * 100 / ($size * $size);

        return $total + 10 * intdiv((int) floor(abs($ratio - 50)), 5);
    }

    private function place(array $codewords): void
    {
        $size = $this->size();
        $bits = '';

        foreach ($codewords as $codeword) {
            $bits .= str_pad(decbin($codeword), 8, '0', STR_PAD_LEFT);
        }

        $length = strlen($bits);
        $index = 0;
        $row = $size - 1;
        $upwards = true;

        for ($column = $size - 1; $column > 0; $column -= 2) {
            // The vertical timing pattern is not part of the zigzag, so the
            // pair of columns shifts one to the left once it is reached.
            if ($column === 6) {
                $column--;
            }

            while (true) {
                foreach ([$column, $column - 1] as $x) {
                    if ($this->reserved[$row][$x]) {
                        continue;
                    }

                    $this->modules[$row][$x] = $index < $length && $bits[$index] === '1';

                    $index++;
                }

                $row += $upwards ? -1 : 1;

                if ($row < 0 || $row >= $size) {
                    $row -= $upwards ? -1 : 1;
                    $upwards = ! $upwards;

                    break;
                }
            }
        }
    }

    private function reserve(int $x, int $y, bool $dark): void
    {
        $size = $this->size();

        if ($x < 0 || $y < 0 || $x >= $size || $y >= $size) {
            return;
        }

        $this->modules[$y][$x] = $dark;
        $this->reserved[$y][$x] = true;
    }
}
