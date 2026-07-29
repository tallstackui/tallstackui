<?php

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
    ->toContain('min-height: 64px')
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
        // A shared scale is what makes the two comparable at a glance.
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

    // A series two orders of magnitude smaller would be a flat line against a
    // shared scale, so it gets a domain of its own.
    expect($component)
        ->render()
        ->toContain('>1,200<')
        ->toContain('>2,000<')
        ->toContain('>8<')
        ->toContain('>16<');
});

it('can format each axis independently', function () {
    // Money on one side does not mean money on the other, so a scalar applies
    // to both while an array picks the side.
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
    // Each axis is pinned to the same tick count, so one set of gridlines
    // serves both and neither side can be misread.
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
    'bar' => ['bar', '<rect'],
    'pie' => ['pie', 'A46,46'],
    'donut' => ['donut', 'A26.68,26.68'],
]);

it('keeps the aspect ratio only on radial types', function (string $type, string $aspect) {
    expect("<x-chart :series=\"[10, 40, 25]\" type=\"{$type}\" />")
        ->render()
        ->toContain('preserveAspectRatio="'.$aspect.'"');
})->with([
    'area' => ['area', 'none'],
    'bar' => ['bar', 'none'],
    // A pie drawn into a stretched viewBox would render as an ellipse.
    'pie' => ['pie', 'xMidYMid meet'],
    'donut' => ['donut', 'xMidYMid meet'],
]);

it('can stack areas onto the curve below', function () {
    // An unstacked area drops to the baseline; a stacked one closes on the
    // curve under it, so only the first band ends up flat at the bottom.
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

    preg_match_all('/<rect[^>]*x="([\d.]+)"[^>]*y="([\d.]+)"/', $html, $rects, PREG_SET_ORDER);

    expect($rects)->toHaveCount(4);

    // Stacked bars share the slot instead of splitting it, so both series sit
    // on the same x, and the second one starts higher up the plot.
    expect($rects[0][1])->toBe($rects[2][1])
        ->and((float) $rects[2][2])->toBeLessThan((float) $rects[0][2]);
});

it('scales a stacked chart against the column total', function () {
    // Against the tallest single value the stack would run off the plot.
    expect('<x-chart type="bar" stacked grid :series="[[\'name\' => \'a\', \'data\' => [60]], [\'name\' => \'b\', \'data\' => [40]]]" />')
        ->render()
        ->toContain('>100<');
});

it('can render a line type without filling the area')
    ->expect('<x-chart :series="[10, 40, 25, 60]" type="line" />')
    ->render()
    ->not->toContain('linearGradient')
    ->not->toContain('L100,96 L0,96 Z');

it('can render bars anchored on zero', function () {
    // Bars measured from their own minimum would misreport every proportion.
    $html = expect('<x-chart :series="[10, 40, 25, 60]" type="bar" />')->render()->value;

    preg_match_all('/height="([\d.]+)"/', $html, $heights);

    expect(min(array_map('floatval', $heights[1])))->toBeGreaterThan(0.0)
        ->and($html)->toContain('<rect');
});

it('can render a full turn as a closed ring', function () {
    // A single arc command cannot express 360 degrees; it has to be split.
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
    // Every displayed number is formatted server-side, so a closure covers
    // the axis and the tooltip alike without crossing over to JavaScript.
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
    // Markers are html, not svg circles: a circle drawn into a viewBox
    // stretched by preserveAspectRatio="none" renders as an ellipse.
    $html = expect('<x-chart :series="[1, 8, 3]" markers />')->render()->value;

    expect(substr_count($html, 'rounded-full'))->toBe(3)
        ->and($html)->toContain('left: 0%')
        ->and($html)->toContain('left: 100%')
        ->not->toContain('x-data')
        ->not->toContain('<circle');
});

it('generates a unique gradient per instance', function () {
    // A shared id would make every chart paint with the first one's colour,
    // because url(#id) resolves to the first match in the document.
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
        'type' => 'bar',
        'height' => 180,
        'grid' => true,
        'legend' => true,
        'tooltip' => true,
        'markers' => true,
        'decimals' => 1,
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-chart :series="[10, 40, 25]" />')
        ->render()
        ->toContain('<rect')
        ->toContain('min-height: 180px')
        ->toContain('<line')
        ->toContain('>10.0<')
        ->toContain('tallstackui_chart(');
});

it('cannot let a config type reach a flag that rejects it', function (string $type) {
    // The mirror of the case below: the flag is explicit and the type comes
    // from the config, so validate() sees a null type and lets it through.
    config()->set('ts-ui.components.chart.1', ['type' => $type]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [10, 40]], [\'name\' => \'b\', \'data\' => [5, 20]]]" stacked legend />')
        ->render()
        ->toMatch('/stacked.{0,8}:false/');
})->with(['line', 'pie', 'donut']);

it('cannot let a config default reach a type that rejects it', function () {
    // validate() runs before configurations, so a global grid would otherwise
    // slip past the rule that rejects it on a radial type.
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
    $this->expectExceptionMessage('The [series] must contain only numeric values.');

    expect($component)->render();
})->with([
    'string' => ['<x-chart :series="[1, \'foo\']" />'],
    'null' => ['<x-chart :series="[1, null]" />'],
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
    // One running total cannot span unrelated magnitudes.
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [stacked] cannot be used together with a secondary axis.');

    expect('<x-chart :series="[[\'name\' => \'a\', \'data\' => [1, 2]], [\'name\' => \'b\', \'data\' => [30, 40], \'axis\' => \'right\']]" stacked />')->render();
});

it('cannot combine grid with a radial type', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [grid] cannot be used with the ['.$type.'] type.');

    expect("<x-chart :series=\"[1, 2, 3]\" type=\"{$type}\" grid />")->render();
})->with(['pie', 'donut']);
