<?php

namespace TallStackUi\Support\QrCode;

use InvalidArgumentException;

final class Encoder
{
    /** Three codewords are held back for the header, which is twenty bits wide here. */
    public static function limit(string $level): int
    {
        return Version::capacity(40, $level) - 3;
    }

    public static function make(string $data, string $level = 'M'): Matrix
    {
        if (! in_array($level, Version::LEVELS, true)) {
            throw new InvalidArgumentException('The error correction level must be one of: '.implode(', ', Version::LEVELS).'.');
        }

        $version = Version::smallest(strlen($data), $level);

        if ($version === null) {
            throw new InvalidArgumentException('The content is too long to be encoded.');
        }

        return (new Matrix($version, $level))->build(Bitstream::of($data, $version, $level));
    }
}
