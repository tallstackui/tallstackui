<?php

namespace TallStackUi\Support\QrCode;

final class Bitstream
{
    private const MODE = 0b0100;

    private const PADDING = [0xEC, 0x11];

    public static function counter(int $version): int
    {
        return $version < 10 ? 8 : 16;
    }

    public static function of(string $data, int $version, string $level): array
    {
        $codewords = self::codewords($data, $version, $level);

        [$check, $sizes] = Version::blocks($version, $level);

        $blocks = [];
        $offset = 0;

        foreach ($sizes as $size) {
            $blocks[] = array_slice($codewords, $offset, $size);

            $offset += $size;
        }

        $corrections = array_map(static fn (array $block): array => Galois::remainder($block, $check), $blocks);

        $result = [];

        for ($index = 0; $index < max($sizes); $index++) {
            foreach ($blocks as $block) {
                if (array_key_exists($index, $block)) {
                    $result[] = $block[$index];
                }
            }
        }

        for ($index = 0; $index < $check; $index++) {
            foreach ($corrections as $correction) {
                $result[] = $correction[$index];
            }
        }

        return $result;
    }

    private static function bits(int $value, int $width): string
    {
        return str_pad(decbin($value), $width, '0', STR_PAD_LEFT);
    }

    private static function codewords(string $data, int $version, string $level): array
    {
        $capacity = Version::capacity($version, $level) * 8;
        $length = strlen($data);

        $bits = self::bits(self::MODE, 4).self::bits($length, self::counter($version));

        for ($index = 0; $index < $length; $index++) {
            $bits .= self::bits(ord($data[$index]), 8);
        }

        // The terminator is up to four zero bits, shortened when the payload
        // already reaches the end of what the version can hold.
        $bits .= str_repeat('0', min(4, $capacity - strlen($bits)));
        $bits .= str_repeat('0', (8 - strlen($bits) % 8) % 8);

        $codewords = array_map('bindec', str_split($bits, 8));
        $filled = count($codewords);

        for ($index = $filled; $index < $capacity / 8; $index++) {
            $codewords[] = self::PADDING[($index - $filled) % 2];
        }

        return $codewords;
    }
}
