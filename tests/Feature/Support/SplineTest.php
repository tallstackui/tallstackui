<?php

use TallStackUi\Support\Charts\Plot;
use TallStackUi\Support\Charts\Scale;
use TallStackUi\Support\Charts\Series;
use TallStackUi\Support\Charts\Spline;

/**
 * Every y coordinate in a path, on-curve points and control points alike.
 * The Fritsch-Carlson guarantee covers the control points too, so asserting
 * over all of them proves the drawn curve, not only the data points.
 */
function ordinates(string $path): array
{
    preg_match_all('/-?\d+(?:\.\d+)?/', $path, $matches);

    return array_values(array_filter(
        $matches[0],
        static fn (string $number, int $index): bool => $index % 2 === 1,
        ARRAY_FILTER_USE_BOTH
    ));
}

function plot(array $values): array
{
    $series = Series::normalize($values);
    $length = Series::length($series);

    return Spline::points($values, Scale::of(Series::values($series)), Spline::indexes($series, $length), $length);
}

function draw(array $values): string
{
    return Spline::path(plot($values));
}

it('never leaves the plot band', function (array $values) {
    $ordinates = array_map('floatval', ordinates(draw($values)));

    // The tolerance is a single rounding unit, not a fudge factor: coordinates
    // are emitted with two decimals, so 4.0 can surface as 3.99 or 4.01.
    expect(min($ordinates))->toBeGreaterThanOrEqual(Plot::INSET - 0.01)
        ->and(max($ordinates))->toBeLessThanOrEqual(Plot::HEIGHT - Plot::INSET + 0.01);
})->with([
    'step' => [[0, 0, 100, 100]],
    'valley' => [[50, 50, 0, 50, 50]],
    'spike' => [[3, 3, 3, 40, 3, 3]],
    'sparkline' => [[4, 6, 5, 12, 3, 8, 7]],
    'plateau then jump' => [[10, 10, 10, 10, 90]],
    'descending step' => [[100, 100, 0, 0]],
    'sawtooth' => [[0, 10, 0, 10, 0, 10, 0]],
]);

it('never leaves the plot band on random series', function () {
    mt_srand(20260728);

    foreach (range(1, 50) as $iteration) {
        $values = array_map(fn () => mt_rand(-500, 500), range(1, mt_rand(2, 40)));
        $ordinates = array_map('floatval', ordinates(draw($values)));

        expect(min($ordinates))->toBeGreaterThanOrEqual(Plot::INSET - 0.01)
            ->and(max($ordinates))->toBeLessThanOrEqual(Plot::HEIGHT - Plot::INSET + 0.01);
    }
});

it('anchors the extremes exactly on the band edges', function (array $values) {
    expect(ordinates(draw($values)))->toContain('4')->toContain('96');
})->with([
    'ascending' => [[1, 2, 3, 4, 5]],
    'spike' => [[3, 3, 3, 40, 3, 3]],
    'negatives' => [[-30, -10, -20, -25]],
    'mixed signs' => [[-5, 0, 5, -2]],
]);

it('never emits a negative coordinate', function (array $values) {
    // One character that catches overshoot, sign bugs and garbage at once.
    expect(draw($values))->not->toContain('-');
})->with([
    'negatives' => [[-30, -10, -20, -25]],
    'mixed signs' => [[-5, 0, 5, -2]],
    'step' => [[0, 0, 100, 100]],
    'all negative' => [[-1, -100, -50]],
]);

it('never emits a non finite coordinate')
    ->expect(strtolower(draw([1, 5, 3, 9])))
    ->not->toContain('nan')
    ->not->toContain('inf');

it('keeps a monotonic series monotonic', function () {
    $ordinates = array_map('floatval', ordinates(draw(range(1, 20))));

    // Ascending values run downwards in SVG space, so every coordinate must
    // be lower than or equal to the one before it. A wiggle between points
    // is exactly what a non-monotone spline would introduce here.
    foreach (array_slice($ordinates, 1) as $index => $ordinate) {
        expect($ordinate)->toBeLessThanOrEqual($ordinates[$index]);
    }
});

it('emits one cubic per segment', function (array $values) {
    expect(substr_count(draw($values), 'C'))->toBe(count($values) - 1);
})->with([
    'two' => [[5, 9]],
    'four' => [[1, 8, 3, 6]],
    'ten' => [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
]);

it('draws two points as a straight line')
    ->expect(draw([5, 9]))
    ->toBe('M0,96 C33.33,65.33 66.67,34.67 100,4');

it('centres a flat series', function (array $values) {
    expect(array_unique(ordinates(draw($values))))->toBe(['50']);
})->with([
    'identical' => [[7, 7, 7, 7]],
    'zeroes' => [[0, 0, 0]],
    // Values apart by float noise describe a flat series: without a relative
    // epsilon the normalization amplifies 5.5e-17 into a full-height zigzag.
    'float noise' => [[0.1 + 0.2, 0.3, 0.3]],
]);

it('downsamples a long series', function () {
    $path = draw(range(1, 500));

    expect(substr_count($path, 'C'))->toBeLessThanOrEqual(Spline::MAX_POINTS)
        ->and(ordinates($path))->toContain('4')->toContain('96');
});

it('keeps an isolated spike while downsampling', function () {
    // A plain stride reducer would drop this peak, and with it the anchor the
    // whole normalization hangs on.
    expect(ordinates(draw([...array_fill(0, 400, 5), 999])))->toContain('4');
});

it('keeps the horizontal axis intact while downsampling', function () {
    // The x of a point follows its original index, so a downsampled series
    // still reads on the same time axis as a dense one.
    $points = plot(range(1, 500));

    expect($points[0][0])->toBe(0.0)
        ->and(end($points)[0])->toBe(Plot::WIDTH);
});

it('shares the downsampled indexes across every series', function () {
    // Curves would drift apart horizontally if each series picked its own.
    $series = Series::normalize([
        ['name' => 'a', 'data' => range(1, 400)],
        ['name' => 'b', 'data' => array_reverse(range(1, 400))],
    ]);

    $indexes = Spline::indexes($series, 400);
    $scale = Scale::of(Series::values($series));

    $first = Spline::points($series[0]['data'], $scale, $indexes, 400);
    $second = Spline::points($series[1]['data'], $scale, $indexes, 400);

    expect(array_column($first, 0))->toBe(array_column($second, 0));
});

it('cannot describe a curve without any point')
    ->expect(plot([]))
    ->toBe([])
    ->and(draw([]))
    ->toBe('');

it('spans a single point across the plot as a constant series', function () {
    // Consistent with [7, 7, 7]: whether one value or three identical ones
    // arrive should not decide if anything is drawn at all.
    expect(plot([5]))->toBe([[0.0, 50.0], [100.0, 50.0]])
        ->and(draw([5]))->toBe('M0,50 C33.33,50 66.67,50 100,50');
});
