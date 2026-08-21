<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use TallStackUi\Components\Chart\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-chart :series="[1, 8, 3, 6]" />')
    ->render()
    ->toContain('<svg')
    ->toContain('viewBox="0 0 100 100"')
    ->toContain('preserveAspectRatio="none"')
    ->toContain('vector-effect="non-scaling-stroke"')
    ->toContain('min-height: 240px')
    ->toContain('stroke-current')
    ->toContain('text-primary-500')
    ->toContain('d="M0,');

it('can render both the area and the line', function () {
    $html = expect('<x-chart :series="[1, 8, 3, 6]" />')->render()->value;

    expect(substr_count($html, '<path'))->toBe(2)
        ->and($html)->toContain('L100,96 L0,96 Z');
});

it('can render a flat series without collapsing the layout')
    ->expect('<x-chart :series="[7, 7, 7]" />')
    ->render()
    ->toContain('d="M0,50');

it('can render with a collection')
    ->expect('<x-chart :series="collect([1, 8, 3, 6])" />')
    ->render()
    ->toContain('d="M0,');

it('can render multiple named series', function () {
    $component = <<<'HTML'
    <x-chart :series="[
        ['name' => '2026', 'data' => [10, 40, 25, 60]],
        ['name' => '2025', 'data' => [8, 30, 33, 41]],
    ]" legend />
    HTML;

    expect($component)
        ->render()
        ->toContain('2026')
        ->toContain('2025')
        ->toContain('text-primary-500')
        ->toContain('text-emerald-500');
});

it('can render a secondary axis', function () {
    $component = <<<'HTML'
    <x-chart :labels="['Jan', 'Fev', 'Mar']"
             :series="[
                 ['name' => 'Receita', 'data' => [1200, 1900, 1500]],
                 ['name' => 'Pedidos', 'data' => [8, 14, 11], 'axis' => 'right'],
             ]"
             grid />
    HTML;

    expect($component)
        ->render()
        ->toContain('>1,200<')
        ->toContain('>2,000<')
        ->toContain('>8<')
        ->toContain('>16<');
});

it('can format each axis independently', function () {
    $component = <<<'HTML'
    <x-chart :series="[
                 ['name' => 'Receita', 'data' => [1200, 1900, 1500]],
                 ['name' => 'Pedidos', 'data' => [8, 14, 11], 'axis' => 'right'],
             ]"
             grid
             :prefix="['left' => 'R$ ']"
             :suffix="['right' => ' un']" />
    HTML;

    expect($component)
        ->render()
        ->toContain('>R$ 1,200<')
        ->toContain('>8 un<')
        ->not->toContain('>R$ 8 un<');
});

it('aligns both axes on the same rows', function () {
    $html = expect(<<<'HTML'
    <x-chart :series="[
        ['name' => 'a', 'data' => [1200, 1900, 1500]],
        ['name' => 'b', 'data' => [8, 14, 11], 'axis' => 'right'],
    ]" grid />
    HTML)->render()->value;

    preg_match_all('/style="top: ([\d.]+)%"/', $html, $matches);

    $rows = array_count_values($matches[1]);

    expect($rows)->toHaveCount(5)
        ->and(array_unique(array_values($rows)))->toBe([2]);
});

it('cannot rescale through the legend when a secondary axis exists', function () {
    // Two domains cannot be carried by one affine pair. Matched loosely
    // because the payload is json escaped into the attribute.
    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2]], [\'name\' => \'b\', \'data\' => [30, 40], \'axis\' => \'right\']]" legend />')
        ->render()
        ->toMatch('/rescale.{0,8}:false/');
});

it('can render each type', function (string $type, string $expected) {
    expect("<x-chart :series=\"[10, 40, 25, 60]\" type=\"{$type}\" />")
        ->render()
        ->toContain($expected);
})->with([
    'area' => ['area', '<path'],
    'line' => ['line', 'stroke-current'],
    'bar' => ['bar', 'A0.6,0.6'],
    'pie' => ['pie', 'A46,46'],
    'donut' => ['donut', 'A26.68,26.68'],
]);

it('can render each type through a flag', function (string $type, string $expected) {
    expect("<x-chart :series=\"[10, 40, 25, 60]\" {$type} />")
        ->render()
        ->toContain($expected);
})->with([
    'area' => ['area', '<path'],
    'line' => ['line', 'stroke-current'],
    'bar' => ['bar', 'A0.6,0.6'],
    'pie' => ['pie', 'A46,46'],
    'donut' => ['donut', 'A26.68,26.68'],
]);

it('can repeat a type as both a flag and an attribute', function () {
    expect('<x-chart :series="[10, 40, 25, 60]" type="bar" bar />')
        ->render()
        ->toContain('A0.6,0.6');
});

it('keeps the aspect ratio only on radial types', function (string $type, string $aspect) {
    expect("<x-chart :series=\"[10, 40, 25]\" type=\"{$type}\" />")
        ->render()
        ->toContain('preserveAspectRatio="'.$aspect.'"');
})->with([
    'area' => ['area', 'none'],
    'bar' => ['bar', 'none'],
    'pie' => ['pie', 'xMidYMid meet'],
    'donut' => ['donut', 'xMidYMid meet'],
]);

it('can stack areas onto the curve below', function () {
    $html = expect(<<<'HTML'
    <x-chart stacked :series="[
        ['name' => 'a', 'data' => [10, 20, 15]],
        ['name' => 'b', 'data' => [5, 10, 8]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/class="stroke-none"\s+fill="url\(#[^)]+\)"\s+d="([^"]+)"/', $html, $areas);

    expect($areas[1])->toHaveCount(2)
        ->and($areas[1][0])->toContain('L0,96 Z')
        ->and($areas[1][1])->not->toContain('L0,96 Z');
});

it('can stack bars onto the running total', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'b', 'data' => [5, 10]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/d="M([\d.]+),([\d.]+)/', $html, $bars, PREG_SET_ORDER);

    expect($bars)->toHaveCount(4);

    expect($bars[0][1])->toBe($bars[2][1])
        ->and((float) $bars[2][2])->toBeLessThan((float) $bars[0][2]);
});

it('hangs a negative segment below the axis', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [10]],
        ['name' => 'b', 'data' => [-6]],
        ['name' => 'c', 'data' => [8]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/d="M[\d.]+,([\d.]+).*?V([\d.]+)/', $html, $spans, PREG_SET_ORDER);

    [$first, $negative, $last] = array_map(
        static fn (array $span): array => [(float) $span[1], (float) $span[2]],
        $spans
    );

    expect(min($negative))->toBe(max($first))
        ->and(max($last))->toBe(min($first));
});

it('does not let a zero value end a stacked column', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [0]],
        ['name' => 'b', 'data' => [20]],
        ['name' => 'c', 'data' => [40]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/d="([^"]+)"/', $html, $bars);

    // The hairline is a bar of its own; the two ends of the column are the
    // base of [b] and the top of [c].
    expect(array_map(static fn (string $path): int => preg_match_all('/A[\d.]+,/', $path), $bars[1]))->toBe([4, 2, 2]);
});

it('treats the axis as a seam when the column crosses it', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [10]],
        ['name' => 'b', 'data' => [-6]],
        ['name' => 'c', 'data' => [8]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/d="([^"]+)"/', $html, $bars);

    // [a] sits between the axis and [c], so it rounds nothing at all.
    expect(array_map(static fn (string $path): int => substr_count($path, 'A0.6,0.6'), $bars[1]))->toBe([0, 2, 2]);
});

it('rounds only the two ends of a stacked column', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [10]],
        ['name' => 'b', 'data' => [5]],
        ['name' => 'c', 'data' => [8]],
    ]" />
    HTML)->render()->value;

    preg_match_all('/d="([^"]+)"/', $html, $bars);

    [$foot, $middle, $head] = array_map(
        static fn (string $path): int => substr_count($path, 'A0.6,0.6'),
        $bars[1]
    );

    expect($foot)->toBe(2)
        ->and($middle)->toBe(0)
        ->and($head)->toBe(2);
});

it('scales a stacked chart against the column total', function () {
    expect('<x-chart type="bar" stacked grid :series="[[\'name\' => \'a\', \'data\' => [60]], [\'name\' => \'b\', \'data\' => [40]]]" />')
        ->render()
        ->toContain('>100<');
});

it('can combine bars and a curve in the same chart', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'total', 'data' => [15, 30], 'type' => 'line'],
    ]" />
    HTML)->render()->value;

    expect(substr_count($html, 'class="fill-current"'))->toBe(2)
        ->and(substr_count($html, 'class="fill-none stroke-current stroke-2"'))->toBe(1);
});

it('aligns a combined curve with the middle of each bar slot', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'total', 'data' => [15, 30], 'type' => 'line'],
    ]" />
    HTML)->render()->value;

    preg_match_all('/class="fill-current" d="([^"]+)"/', $html, $bars);

    // The move, the horizontal lines and the point each corner arc lands on
    // are the coordinates that carry an x, and they bound the bar.
    $centres = array_map(static function (string $path): float {
        preg_match_all('/(?:M|H|0,1 )([\d.]+)/', $path, $abscissas);

        $abscissas = array_map('floatval', $abscissas[1]);

        return (min($abscissas) + max($abscissas)) / 2;
    }, $bars[1]);

    expect($centres)->toBe([25.0, 75.0])
        ->and($html)->toContain('d="M25,')
        ->and($html)->toContain(' 75,');
});

it('keeps a combined curve out of the stack', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" stacked :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'b', 'data' => [5, 10]],
        ['name' => 'total', 'data' => [10, 20], 'type' => 'line'],
    ]" />
    HTML)->render()->value;

    // The domain tops out at the column total of 30, so 10 lands on 65.33.
    // Stacked, the same value would have been drawn at 25.
    expect($html)->toContain('d="M25,65.33');
});

it('reads the axis in slots whenever a bar is combined in', function () {
    $html = expect(<<<'HTML'
    <x-chart type="line" tooltip :labels="['Jan', 'Fev']" :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'b', 'data' => [15, 30], 'type' => 'bar'],
    ]" />
    HTML)->render()->value;

    expect($html)->toContain('left: 25%')
        ->and($html)->toContain('left: 75%')
        ->and($html)->toContain('slotted\\u0022:true');
});

it('draws markers only over the series that have a curve', function () {
    $html = expect(<<<'HTML'
    <x-chart type="bar" markers :series="[
        ['name' => 'a', 'data' => [10, 20]],
        ['name' => 'total', 'data' => [15, 30], 'type' => 'line'],
    ]" />
    HTML)->render()->value;

    expect(substr_count($html, 'rounded-full'))->toBe(2);
});

it('can render a line type without filling the area')
    ->expect('<x-chart :series="[10, 40, 25, 60]" type="line" />')
    ->render()
    ->not->toContain('linearGradient')
    ->not->toContain('L100,96 L0,96 Z');

it('can render bars anchored on zero', function () {
    $html = expect('<x-chart :series="[10, 40, 25, 60]" type="bar" />')->render()->value;

    preg_match_all('/M[\d.]+,([\d.]+)/', $html, $tops);

    expect($tops[1])->toHaveCount(4)
        ->and(max(array_map('floatval', $tops[1])))->toBeLessThan(90.0);
});

it('can render a full turn as a closed ring', function () {
    expect('<x-chart :series="[10]" type="pie" />')
        ->render()
        ->toContain('A46,46 0 1,1');
});

it('can render the axis labels from the labels attribute')
    ->expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\', \'Fev\', \'Mar\']" />')
    ->render()
    ->toContain('Jan')
    ->toContain('Mar')
    ->toContain('left: 100%');

it('thins the axis labels from the browser', function () {
    expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\', \'Fev\', \'Mar\']" />')
        ->render()
        ->toContain('x-data="tallstackui_chartAxis({ fit: \'thin\' })"')
        ->toContain('shown(0)')
        ->toContain('shown(2)');
});

it('does not attach the axis thinning without labels')
    ->expect('<x-chart :series="[10, 40, 25]" />')
    ->render()
    ->not->toContain('tallstackui_chartAxis');

it('thins the axis labels by default')
    ->expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\', \'Fev\', \'Mar\']" />')
    ->render()
    ->toContain("tallstackui_chartAxis({ fit: 'thin' })");

it('can pick how the axis labels fit', function (string $fit) {
    expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\', \'Fev\', \'Mar\']" fit="'.$fit.'" />')
        ->render()
        ->toContain("tallstackui_chartAxis({ fit: '".$fit."' })");
})->with(['thin', 'rotate', 'stagger']);

it('can set the axis fit globally', function () {
    config()->set('ts-ui.components.chart.1', ['fit' => 'rotate']);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\', \'Fev\', \'Mar\']" />')
        ->render()
        ->toContain("tallstackui_chartAxis({ fit: 'rotate' })");
});

it('cannot use an unknown fit', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [fit] must be one of: thin, rotate, stagger.');

    expect('<x-chart :series="[10, 40, 25]" :labels="[\'Jan\']" fit="wrap" />')->render();
});

it('joins the points with a monotone curve by default')
    ->expect('<x-chart :series="[10, 40, 25]" />')
    ->render()
    ->toContain(' C')
    ->not->toContain(' L50,')
    ->not->toContain(' H');

it('can join the points with straight lines', function () {
    $html = expect('<x-chart :series="[10, 40, 25]" curve="straight" />')->render()->value;

    expect($html)
        ->toContain('d="M0,')
        ->toContain(' L50,')
        ->toContain(' L100,')
        ->not->toContain(' C')
        ->toContain(' L100,96 L0,96 Z');
});

it('can join the points with steps', function () {
    $html = expect('<x-chart :series="[10, 40, 25]" curve="step" />')->render()->value;

    expect($html)
        ->toContain(' H50 V')
        ->toContain(' H100 V')
        ->not->toContain(' C');
});

it('can pick the curve per series', function () {
    $component = <<<'HTML'
    <x-chart line :series="[
        ['name' => 'Smooth', 'data' => [10, 40, 25]],
        ['name' => 'Straight', 'data' => [8, 30, 33], 'curve' => 'straight'],
    ]" />
    HTML;

    preg_match_all('/ d="([^"]+)"/', expect($component)->render()->value, $matches);

    expect($matches[1][0])->toContain(' C')
        ->and($matches[1][1])->toContain(' L')->not->toContain(' C');
});

it('walks a stacked step band back the way it came', function () {
    $component = <<<'HTML'
    <x-chart stacked curve="step" :series="[
        ['name' => 'Below', 'data' => [10, 40, 25]],
        ['name' => 'Above', 'data' => [8, 30, 33]],
    ]" />
    HTML;

    preg_match_all('/fill="url\(#[^"]+\)"\s+d="([^"]+)"/', expect($component)->render()->value, $matches);

    // The lower edge drops before it runs back, tracing the upper edge of
    // the band below rather than cutting across its corners.
    expect($matches[1][1])->toMatch('/ L100,[\d.]+ V[\d.]+ H50 V[\d.]+ H0 Z$/');
});

it('can set the curve globally', function () {
    $original = config('ts-ui.components.chart.1');

    config()->set('ts-ui.components.chart.1', ['curve' => 'straight']);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-chart :series="[10, 40, 25]" />')
            ->render()
            ->toContain(' L50,')
            ->not->toContain(' C');
    } finally {
        config()->set('ts-ui.components.chart.1', $original);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an unknown curve', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [curve] must be one of: smooth, straight, step.');

    expect('<x-chart :series="[10, 40, 25]" curve="wavy" />')->render();
});

it('cannot use an unknown curve on a series', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [curve] of every series must be one of: smooth, straight, step.');

    expect('<x-chart :series="[[\'data\' => [10, 40], \'curve\' => \'wavy\']]" />')->render();
});

it('cannot shape what a radial type does not draw', function (string $attribute) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The ['.$attribute.'] cannot be used with the [pie] type.');

    expect('<x-chart :series="[10, 40, 25]" pie '.$attribute.'="'.['curve' => 'straight', 'round' => 'lg', 'corners' => 'end'][$attribute].'" />')->render();
})->with(['curve', 'round', 'corners']);

it('lets a global curve reach a radial type without complaint', function () {
    $original = config('ts-ui.components.chart.1');

    config()->set('ts-ui.components.chart.1', ['curve' => 'straight', 'round' => 'lg', 'corners' => 'end']);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-chart :series="[10, 40, 25]" pie />')->render()->toContain('<svg');
    } finally {
        config()->set('ts-ui.components.chart.1', $original);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('breaks the line at a gap', function () {
    $html = expect('<x-chart :series="[10, 40, null, 25, 60]" line markers />')->render()->value;

    preg_match('/stroke-linejoin="round"\s+d="([^"]+)"/', $html, $line);

    // Two runs, each with its own move, and no segment across the hole.
    expect(substr_count($line[1], 'M'))->toBe(2)
        ->and($line[1])->not->toContain('50,')
        ->and(substr_count($html, 'rounded-full bg-current'))->toBe(4);
});

it('closes the area on each side of a gap', function () {
    $html = expect('<x-chart :series="[10, 40, null, 25, 60]" />')->render()->value;

    preg_match('/fill="url\(#[^"]+\)"\s+d="([^"]+)"/', $html, $area);

    expect(substr_count($area[1], ' Z'))->toBe(2);
});

it('keeps a lone point between gaps as a marker with no line', function () {
    $html = expect('<x-chart :series="[null, 40, null]" markers />')->render()->value;

    expect($html)->not->toContain('fill-none stroke-current')
        ->and(substr_count($html, 'rounded-full bg-current'))->toBe(1);
});

it('leaves a gap out of the scale', function () {
    expect('<x-chart :series="[10, null, 40]" grid />')
        ->render()
        ->toContain('>10</span>')
        ->not->toContain('>0</span>');
});

it('draws no bar at a gap', function () {
    $html = expect('<x-chart :series="[10, null, 25]" bar />')->render()->value;

    expect(substr_count($html, 'class="fill-current"'))->toBe(2);
});

it('stacks over a gap as if it were nothing', function () {
    $component = <<<'HTML'
    <x-chart bar stacked :series="[
        ['name' => 'Below', 'data' => [10, null, 25]],
        ['name' => 'Above', 'data' => [8, 30, 33]],
    ]" />
    HTML;

    preg_match_all('/<path class="fill-current" d="([^"]+)"/', expect($component)->render()->value, $bars);

    // Five bars, and the one above the gap reaches the axis with both of its
    // ends rounded, since nothing sits under it.
    expect($bars[1])->toHaveCount(5)
        ->and($bars[1][3])->toContain(' V95.4 ')
        ->and(substr_count($bars[1][3], 'A'))->toBe(4);
});

it('sends a gap to the tooltip as nothing to show')
    ->expect('<x-chart :series="[10, null, 25]" tooltip />')
    ->render()
    ->toContain('\u0022formatted\u0022:[\u002210\u0022,null,\u002225\u0022]');

it('weighs a gap as nothing on a radial type', function () {
    $html = expect('<x-chart :series="[30, null, 70]" pie />')->render()->value;

    expect(substr_count($html, 'stroke-white'))->toBe(2);
});

it('rounds every corner of a bar by default', function () {
    preg_match('/<path class="fill-current" d="([^"]+)"/', expect('<x-chart :series="[10, 40, 25]" bar />')->render()->value, $bar);

    expect(substr_count($bar[1], 'A0.6,0.6'))->toBe(4);
});

it('can round only the end of a bar', function () {
    $html = expect('<x-chart :series="[10, -40, 25]" bar corners="end" />')->render()->value;

    preg_match_all('/<path class="fill-current" d="([^"]+)"/', $html, $bars);

    // A positive bar rounds its head, a negative one its foot.
    expect(substr_count($bars[1][0], 'A'))->toBe(2)
        ->and($bars[1][0])->toMatch('/^M[\d.]+,[\d.]+ A/')
        ->and($bars[1][1])->toMatch('/^M[\d.]+,[\d.]+ H/')
        ->and(substr_count($bars[1][1], 'A'))->toBe(2);
});

it('rounds only the far end of a stacked column with end corners', function () {
    $component = <<<'HTML'
    <x-chart bar stacked corners="end" :series="[
        ['name' => 'Below', 'data' => [10, 40]],
        ['name' => 'Above', 'data' => [8, 30]],
    ]" />
    HTML;

    preg_match_all('/<path class="fill-current" d="([^"]+)"/', expect($component)->render()->value, $bars);

    expect(substr_count($bars[1][0], 'A'))->toBe(0)
        ->and(substr_count($bars[1][2], 'A'))->toBe(2);
});

it('can pick the corner radius', function (string $round, string $arc) {
    preg_match('/<path class="fill-current" d="([^"]+)"/', expect('<x-chart :series="[10, 40, 25]" bar round="'.$round.'" />')->render()->value, $bar);

    expect($bar[1])->toContain($arc);
})->with([
    'md' => ['md', 'A1.2,1.2'],
    'lg' => ['lg', 'A2.4,2.4'],
]);

it('can square the bars off')
    ->expect('<x-chart :series="[10, 40, 25]" bar round="none" />')
    ->render()
    ->not->toContain(' A');

it('can set the corners globally', function () {
    $original = config('ts-ui.components.chart.1');

    config()->set('ts-ui.components.chart.1', ['round' => 'md', 'corners' => 'end']);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        preg_match('/<path class="fill-current" d="([^"]+)"/', expect('<x-chart :series="[10, 40, 25]" bar />')->render()->value, $bar);

        expect(substr_count($bar[1], 'A1.2,1.2'))->toBe(2);
    } finally {
        config()->set('ts-ui.components.chart.1', $original);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an unknown round', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [round] must be one of: none, sm, md, lg.');

    expect('<x-chart :series="[10, 40, 25]" bar round="xl" />')->render();
});

it('cannot use unknown corners', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [corners] must be one of: all, end.');

    expect('<x-chart :series="[10, 40, 25]" bar corners="top" />')->render();
});

it('can render a grid with rounded tick values', function () {
    // Ticks exist so the axis reads as 0, 20, 40 rather than 10, 27.5, 45.
    expect('<x-chart :series="[10, 40, 25, 63]" grid />')
        ->render()
        ->toContain('<line')
        ->toContain('>0<')
        ->toContain('>20<')
        ->toContain('>40<')
        ->toContain('>60<');
});

it('can format through a closure', function () {
    $component = <<<'HTML'
    <x-chart :series="[12000, 25000, 18000]" grid tooltip
             :formatter="fn (float $value) => 'R$ '.number_format($value, 2, ',', '.')" />
    HTML;

    expect($component)
        ->render()
        ->toContain('>R$ 10.000,00<')
        ->toContain('R$ 12.000,00');
});

it('can format each axis through the same closure', function () {
    $component = <<<'HTML'
    <x-chart grid
             :series="[
                 ['name' => 'Receita', 'data' => [1200, 1900]],
                 ['name' => 'Pedidos', 'data' => [8, 14], 'axis' => 'right'],
             ]"
             :formatter="fn (float $value, string $axis) => $axis === 'right' ? $value.' un' : 'R$ '.number_format($value, 0, ',', '.')" />
    HTML;

    expect($component)
        ->render()
        ->toContain('>R$ 1.200<')
        ->toContain('>8 un<');
});

it('lets the closure win over prefix, suffix and decimals')
    ->expect('<x-chart :series="[1000, 2000]" grid prefix="US$ " :decimals="2" :formatter="fn (float $value) => \'BRL \'.$value" />')
    ->render()
    ->toContain('BRL 1000')
    ->not->toContain('US$');

it('can format the axis values')
    ->expect('<x-chart :series="[1000, 4000, 2500]" grid prefix="R$ " :decimals="2" />')
    ->render()
    ->toContain('R$ 1,000.00');

it('attaches alpine only when something needs it', function (string $component, bool $interactive) {
    $expectation = expect($component)->render();

    $interactive
        ? $expectation->toContain('tallstackui_chart(')
        : $expectation->not->toContain('tallstackui_chart(');
})->with([
    'plain' => ['<x-chart :series="[1, 2, 3]" />', false],
    'markers only' => ['<x-chart :series="[1, 2, 3]" markers />', false],
    'grid only' => ['<x-chart :series="[1, 2, 3]" grid />', false],
    'tooltip' => ['<x-chart :series="[1, 2, 3]" tooltip />', true],
    'legend' => ['<x-chart :series="[1, 2, 3]" legend />', true],
]);

it('can render markers without alpine', function () {
    $html = expect('<x-chart :series="[1, 8, 3]" markers />')->render()->value;

    expect(substr_count($html, 'rounded-full'))->toBe(3)
        ->and($html)->toContain('left: 0%')
        ->and($html)->toContain('left: 100%')
        ->not->toContain('x-data')
        ->not->toContain('<circle');
});

it('generates a unique gradient per instance', function () {
    $html = expect('<x-chart :series="[1, 2, 3]" color="emerald" /><x-chart :series="[3, 2, 1]" color="rose" />')
        ->render()
        ->value;

    preg_match_all('/id="(tsui-chart-[^"]+)"/', $html, $ids);
    preg_match_all('/url\(#(tsui-chart-[^)]+)\)/', $html, $references);

    expect($ids[1])->toHaveCount(2)
        ->and(array_unique($ids[1]))->toHaveCount(2)
        ->and($references[1])->toBe($ids[1])
        ->and($html)->toContain('text-emerald-500')->toContain('text-rose-500');
});

it('can render with colors', function (string $color) {
    expect("<x-chart :series=\"[1, 8, 3, 6]\" color=\"{$color}\" />")
        ->render()
        ->toContain("text-{$color}-500")
        ->toContain('stop-color="currentColor"');
})->with(['red', 'green', 'emerald', 'rose']);

it('can override the palette per series')
    ->expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2]], [\'name\' => \'b\', \'data\' => [2, 1]]]" :colors="[\'sky\', \'amber\']" />')
    ->render()
    ->toContain('text-sky-500')
    ->toContain('text-amber-500');

it('can default every chart through the config', function () {
    config()->set('ts-ui.components.chart.1', [
        'height' => 180,
        'grid' => true,
        'legend' => true,
        'tooltip' => true,
        'markers' => true,
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [10, 40, 25]]]" />')
        ->render()
        ->toContain('min-height: 180px')
        ->toContain('<line')
        ->toContain('rounded-full')
        ->toContain('tallstackui_chart_legend_0')
        ->toContain('tallstackui_chart_tooltip')
        ->toContain('tallstackui_chart(');
});

it('cannot let a config default reach a type that rejects it', function () {
    config()->set('ts-ui.components.chart.1', ['grid' => true]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-chart :series="[10, 40, 25]" type="donut" />')
        ->render()
        ->not->toContain('<line');
});

it('can render with a custom height')
    ->expect('<x-chart :series="[1, 2, 3]" height="120" />')
    ->render()
    ->toContain('min-height: 120px');

it('can render the header and footer slots', function () {
    $component = <<<'HTML'
    <x-chart :series="[1, 2, 3]">
        <x-slot:header>Balance</x-slot:header>
        <x-slot:footer>Last 12 months</x-slot:footer>
    </x-chart>
    HTML;

    expect($component)->render()->toContain('Balance')->toContain('Last 12 months');
});

it('can render an empty series without collapsing the layout')
    ->expect('<x-chart :series="[]" />')
    ->render()
    ->toContain('<svg')
    ->not->toContain('<path')
    ->not->toContain('linearGradient');

it('can render a single point as a constant series')
    ->expect('<x-chart :series="[5]" />')
    ->render()
    ->toContain('d="M0,50')
    ->toContain('L100,50');

it('cannot render without series', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [series] attribute is required.');

    expect('<x-chart />')->render();
});

it('cannot render with non numeric series', function (string $component) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [series] must contain only numeric values, or null for a gap.');

    expect($component)->render();
})->with([
    'string' => ['<x-chart :series="[1, \'foo\']" />'],
    'nan' => ['<x-chart :series="[1, NAN]" />'],
    'inf' => ['<x-chart :series="[1, INF]" />'],
    'nested' => ['<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, \'foo\']]]" />'],
]);

it('cannot render a series entry without data', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Every entry of [series] must carry a [data] key.');

    expect('<x-chart :series="[[\'name\' => \'a\']]" />')->render();
});

it('cannot render with an unknown type', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [type] must be one of: area, line, bar, pie, donut.');

    expect('<x-chart :series="[1, 2, 3]" type="radar" />')->render();
});

it('cannot combine two type flags', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Only one type can be used at a time, but [line, bar] were given.');

    expect('<x-chart :series="[1, 2, 3]" line bar />')->render();
});

it('cannot combine a type flag with a type that contradicts it', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [type] and the [bar] flag cannot be used together.');

    expect('<x-chart :series="[1, 2, 3]" type="line" bar />')->render();
});

it('cannot render with an invalid height', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [height] must be greater than 0.');

    expect('<x-chart :series="[1, 2, 3]" height="0" />')->render();
});

it('cannot combine stacked with an unstackable type', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [stacked] cannot be used with the ['.$type.'] type.');

    expect("<x-chart :series=\"[1, 2, 3]\" type=\"{$type}\" stacked />")->render();
})->with(['line', 'pie', 'donut']);

it('cannot render with an unknown axis', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [axis] of every series must be one of: left, right.');

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2], \'axis\' => \'top\']]" />')->render();
});

it('cannot render with an unknown series type', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [type] of every series must be one of: area, line, bar.');

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2], \'type\' => \'donut\']]" />')->render();
});

it('cannot render a radial type from more than one series', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The ['.$type.'] type accepts only one series.');

    expect("<x-chart :series=\"[['name' => 'a', 'data' => [1, 2]], ['name' => 'b', 'data' => [3, 4]]]\" type=\"{$type}\" />")->render();
})->with(['pie', 'donut']);

it('cannot combine a series type with a radial type', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [type] of a series cannot be used with the ['.$type.'] type.');

    expect("<x-chart :series=\"[['name' => 'a', 'data' => [1, 2], 'type' => 'line']]\" type=\"{$type}\" />")->render();
})->with(['pie', 'donut']);

it('cannot format with an unknown axis key', function (string $prop) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The ['.$prop.'] must only use the keys: left, right.');

    expect("<x-chart :series=\"[1, 2, 3]\" :{$prop}=\"['top' => 'x']\" />")->render();
})->with(['prefix', 'suffix']);

it('cannot render with invalid decimals', function (string $component) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [decimals] must be an integer greater than or equal to 0.');

    expect($component)->render();
})->with([
    'negative' => ['<x-chart :series="[1, 2, 3]" :decimals="-1" />'],
    'non integer' => ['<x-chart :series="[1, 2, 3]" :decimals="[\'left\' => \'abc\']" />'],
]);

it('cannot stack across two axes', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [stacked] cannot be used together with a secondary axis.');

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2]], [\'name\' => \'b\', \'data\' => [30, 40], \'axis\' => \'right\']]" stacked />')->render();
});

it('cannot combine grid with a radial type', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [grid] cannot be used with the ['.$type.'] type.');

    expect("<x-chart :series=\"[1, 2, 3]\" type=\"{$type}\" grid />")->render();
})->with(['pie', 'donut']);

it('can render the skeleton without a series')
    ->expect('<x-chart skeleton />')
    ->render()
    ->toContain('animate-pulse')
    ->toContain('tallstackui_chart_skeleton');

it('keys the skeleton so livewire swaps it rather than morphing it')
    ->expect('<x-chart skeleton />')
    ->render()
    ->toContain('wire:key="tallstackui-chart-skeleton-');

it('can render the skeleton as a curve')
    ->expect('<x-chart skeleton type="line" />')
    ->render()
    ->toContain('stroke-gray-200')
    ->not->toContain('<rect');

it('can render the skeleton as an area with the closed path')
    ->expect('<x-chart skeleton type="area" />')
    ->render()
    ->toContain('fill-gray-200')
    ->toContain('stroke-gray-200');

it('can render the skeleton as bars', function () {
    $html = Blade::render('<x-chart skeleton="4" type="bar" />');

    expect(substr_count($html, '<path'))->toBe(4);
});

it('can render the skeleton as slices', function () {
    $html = Blade::render('<x-chart skeleton="5" type="donut" />');

    expect(substr_count($html, '<path'))->toBe(5);
});

it('can render the skeleton keeping the resolved height')
    ->expect('<x-chart skeleton :height="320" />')
    ->render()
    ->toContain('min-height: 320px');

it('can render the skeleton without any element that would read as data')
    ->expect('<x-chart skeleton legend tooltip markers grid />')
    ->render()
    ->not->toContain('tallstackui_chart(')
    ->not->toContain('flex flex-wrap items-center justify-center gap-x-4')
    ->not->toContain('absolute right-2 -translate-y-1/2');

it('still validates the type in skeleton mode', function () {
    $this->expectException(ViewException::class);

    expect('<x-chart skeleton type="foo" />')->render();
});

it('still validates the height in skeleton mode', function () {
    $this->expectException(ViewException::class);

    expect('<x-chart skeleton :height="0" />')->render();
});

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-chart skeleton="0" />')->render();
});

it('addresses donut slices by position when a value is zero', function () {
    $component = '<x-chart donut :series="[45, 0, 18]" :labels="[\'Direto\', \'Busca\', \'Social\']" legend tooltip />';

    expect($component)->render()
        ->toContain('x-bind:d="arc(0)"')
        ->toContain('x-bind:d="arc(1)"')
        ->not->toContain('x-bind:d="arc(2)"')
        ->toContain('toggle(0)')
        ->toContain('toggle(1)')
        ->not->toContain('toggle(2)');
});
