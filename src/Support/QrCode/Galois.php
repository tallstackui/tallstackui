<?php

namespace TallStackUi\Support\QrCode;

/**
 * Arithmetic over GF(256), the field used for error correction.
 *
 * Elements are bytes: addition is XOR, multiplication is a log table lookup.
 */
final class Galois
{
    /** Primitive polynomial x^8 + x^4 + x^3 + x^2 + 1, fixed by the specification. */
    private const PRIMITIVE = 0x11D;

    private static array $exponents = [];

    private static array $generators = [];

    private static array $logarithms = [];

    /** Check codewords: the data polynomial divided by the generator, remainder kept. */
    public static function remainder(array $data, int $degree): array
    {
        self::tables();

        $generator = self::generator($degree);
        $length = count($data);
        $result = array_merge($data, array_fill(0, $degree, 0));

        for ($index = 0; $index < $length; $index++) {
            $factor = $result[$index];

            if ($factor === 0) {
                continue;
            }

            // The generator is monic, so this also clears the leading term.
            foreach ($generator as $offset => $coefficient) {
                $result[$index + $offset] ^= self::multiply($coefficient, $factor);
            }
        }

        return array_slice($result, $length, $degree);
    }

    /** Product of (x + a^i) up to the degree, highest term first. */
    private static function generator(int $degree): array
    {
        if (isset(self::$generators[$degree])) {
            return self::$generators[$degree];
        }

        $polynomial = [1];

        for ($index = 0; $index < $degree; $index++) {
            $next = array_fill(0, count($polynomial) + 1, 0);

            foreach ($polynomial as $offset => $coefficient) {
                $next[$offset] ^= $coefficient;
                $next[$offset + 1] ^= self::multiply($coefficient, self::$exponents[$index]);
            }

            $polynomial = $next;
        }

        return self::$generators[$degree] = $polynomial;
    }

    private static function multiply(int $left, int $right): int
    {
        if ($left === 0 || $right === 0) {
            return 0;
        }

        return self::$exponents[self::$logarithms[$left] + self::$logarithms[$right]];
    }

    /** Exponents run past 255 so a sum of logarithms indexes without wrapping. */
    private static function tables(): void
    {
        if (self::$exponents !== []) {
            return;
        }

        $value = 1;

        for ($index = 0; $index < 255; $index++) {
            self::$exponents[$index] = $value;
            self::$logarithms[$value] = $index;

            $value <<= 1;

            if ($value & 0x100) {
                $value ^= self::PRIMITIVE;
            }
        }

        for ($index = 255; $index < 512; $index++) {
            self::$exponents[$index] = self::$exponents[$index - 255];
        }
    }
}
