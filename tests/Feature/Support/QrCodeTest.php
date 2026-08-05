<?php

use TallStackUi\Support\QrCode\Encoder;
use TallStackUi\Support\QrCode\Galois;
use TallStackUi\Support\QrCode\Matrix;
use TallStackUi\Support\QrCode\Path;
use TallStackUi\Support\QrCode\Version;
use TallStackUi\Support\QrCode\Watermark;

/**
 * Total codewords a version holds, counted from the geometry instead of read
 * from a table. Every module that is not a function pattern carries data, so
 * subtracting the patterns from the area and dividing by eight has to agree
 * with the block table the encoder actually uses.
 */
function codewords(int $version): int
{
    $size = Version::size($version);
    $centres = count((new ReflectionClass(Version::class))->getConstant('ALIGNMENTS')[$version]);

    $functions = 192 + 2 * ($size - 16) + 31 + ($version >= 7 ? 36 : 0);

    if ($centres > 0) {
        $functions += 25 * ($centres * $centres - 3) - 10 * ($centres - 2);
    }

    return intdiv($size * $size - $functions, 8);
}

function dark(Matrix $matrix): int
{
    return array_sum(array_map(static fn (array $row): int => count(array_filter($row)), $matrix->toArray()));
}

/**
 * A different route to GF(256) than the log tables the encoder builds, so a
 * fault in those cannot hide behind itself here.
 */
function gf(int $left, int $right): int
{
    $result = 0;

    while ($right > 0) {
        if ($right & 1) {
            $result ^= $left;
        }

        $right >>= 1;
        $left <<= 1;

        if ($left & 0x100) {
            $left ^= 0x11D;
        }
    }

    return $result;
}

function alpha(int $exponent): int
{
    $result = 1;

    for ($index = 0; $index < $exponent; $index++) {
        $result = gf($result, 2);
    }

    return $result;
}

/** Horner evaluation of a polynomial given highest term first. */
function horner(array $coefficients, int $x): int
{
    $result = 0;

    foreach ($coefficients as $coefficient) {
        $result = gf($result, $x) ^ $coefficient;
    }

    return $result;
}

it('produces the published check codewords', function () {
    // The worked example of the specification: HELLO WORLD at version one,
    // level M, down to the padding codewords.
    $data = [32, 91, 11, 120, 209, 114, 220, 77, 67, 64, 236, 17, 236, 17, 236, 17];

    expect(Galois::remainder($data, 10))->toBe([196, 35, 39, 119, 235, 215, 231, 226, 93, 23]);
});

it('produces check codewords that vanish at every root', function () {
    // A Reed-Solomon codeword is a multiple of the generator polynomial, so it
    // evaluates to zero at each of its roots. Asserting the property rather
    // than a second implementation is what makes this independent.
    $degrees = [];

    foreach (range(1, 40) as $version) {
        foreach (Version::LEVELS as $level) {
            [$check] = Version::blocks($version, $level);

            $degrees[$check] = true;
        }
    }

    expect(array_keys($degrees))->toHaveCount(13);

    $data = array_map(static fn (int $index): int => ($index * 37 + 11) % 256, range(0, 29));

    foreach (array_keys($degrees) as $degree) {
        $codeword = [...$data, ...Galois::remainder($data, $degree)];

        foreach (range(0, $degree - 1) as $root) {
            expect(horner($codeword, alpha($root)))->toBe(0, "degree {$degree}, root {$root}");
        }
    }
});

it('agrees with the geometry on every version and level', function () {
    foreach (range(1, 40) as $version) {
        foreach (Version::LEVELS as $level) {
            [$check, $sizes] = Version::blocks($version, $level);

            expect(array_sum($sizes) + $check * count($sizes))
                ->toBe(codewords($version), "version {$version}, level {$level}");
        }
    }
});

it('drops the three alignment patterns that collide with a finder', function () {
    expect(Version::alignments(1))->toBeEmpty()
        ->and(Version::alignments(2))->toHaveCount(1)
        ->and(Version::alignments(7))->toHaveCount(6)
        ->and(Version::alignments(40))->toHaveCount(46);
});

it('picks the smallest version that fits', function () {
    // The header costs a byte and a half below version ten, so the last
    // payload version nine takes is two codewords short of its capacity.
    expect(Version::smallest(Version::capacity(9, 'M') - 2, 'M'))->toBe(9)
        ->and(Version::smallest(Version::capacity(9, 'M') - 1, 'M'))->toBe(10)
        ->and(Version::smallest(Version::capacity(40, 'M'), 'M'))->toBeNull();
});

it('encodes into a grid of the right shape', function (string $level, int $version) {
    $matrix = Encoder::make('https://tallstackui.com', $level);

    expect($matrix->version)->toBe($version)
        ->and($matrix->size())->toBe(17 + 4 * $version)
        ->and($matrix->toArray())->toHaveCount($matrix->size());
})->with([
    ['L', 2],
    ['M', 2],
    ['Q', 3],
    ['H', 3],
]);

it('places the three finders and the dark module', function () {
    $matrix = Encoder::make('https://tallstackui.com');
    $rows = $matrix->toArray();
    $size = $matrix->size();

    foreach ([[0, 0], [$size - 7, 0], [0, $size - 7]] as [$x, $y]) {
        foreach (range(0, 6) as $offset) {
            expect($rows[$y][$x + $offset])->toBeTrue()
                ->and($rows[$y + 6][$x + $offset])->toBeTrue()
                ->and($rows[$y + $offset][$x])->toBeTrue()
                ->and($rows[$y + $offset][$x + 6])->toBeTrue();
        }
    }

    expect($rows[$size - 8][8])->toBeTrue();
});

it('alternates the timing patterns', function () {
    $matrix = Encoder::make('https://tallstackui.com');
    $rows = $matrix->toArray();

    foreach (range(8, $matrix->size() - 9) as $index) {
        expect($rows[6][$index])->toBe($index % 2 === 0)
            ->and($rows[$index][6])->toBe($index % 2 === 0);
    }
});

it('encodes the same payload the same way every time', function () {
    expect(Path::of(Encoder::make('https://tallstackui.com')))
        ->toBe(Path::of(Encoder::make('https://tallstackui.com')));
});

it('rejects a payload no version can carry', function () {
    Encoder::make(str_repeat('a', 3000));
})->throws(InvalidArgumentException::class, 'The content is too long to be encoded.');

it('rejects an unknown error correction level', function () {
    Encoder::make('https://tallstackui.com', 'Z');
})->throws(InvalidArgumentException::class);

it('surrounds the drawing with the quiet zone', function () {
    $matrix = Encoder::make('https://tallstackui.com');

    expect(Path::viewbox($matrix))->toBe('0 0 '.($matrix->size() + 8).' '.($matrix->size() + 8));
});

it('merges neighbouring modules into a single run', function () {
    $matrix = Encoder::make('https://tallstackui.com');

    // One subpath per run of dark modules, which a finder alone already makes
    // fewer than one per module.
    expect(substr_count(Path::of($matrix), 'M'))->toBeLessThan(dark($matrix));
});

it('leaves the watermark region blank', function () {
    $matrix = Encoder::make('https://tallstackui.com', 'H');
    $knockout = Watermark::icon($matrix->version)['knockout'];
    $rows = $matrix->toArray();

    $removed = 0;

    for ($y = $knockout['y']; $y < $knockout['y'] + $knockout['height']; $y++) {
        for ($x = $knockout['x']; $x < $knockout['x'] + $knockout['width']; $x++) {
            $removed += $rows[$y][$x] ? 1 : 0;
        }
    }

    expect(substr_count(Path::of($matrix, $knockout), 'v1'))
        ->toBeLessThan(substr_count(Path::of($matrix), 'v1'))
        ->and($removed)->toBeGreaterThan(0);
});

it('keeps the watermark clear of the timing and format modules', function (int $version) {
    $size = Version::size($version);

    foreach ([Watermark::icon($version)['knockout'], Watermark::text($version, 8)['knockout']] as $knockout) {
        expect($knockout['x'])->toBeGreaterThan(8)
            ->and($knockout['y'])->toBeGreaterThan(8)
            ->and($knockout['x'] + $knockout['width'])->toBeLessThan($size - 8)
            ->and($knockout['y'] + $knockout['height'])->toBeLessThan($size - 8);
    }
})->with([1, 2, 5, 10, 20, 40]);
