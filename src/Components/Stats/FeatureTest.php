<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use TallStackUi\Components\Stats\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-stats number="33" />')
    ->render()
    ->toContain('33')
    ->toContain('<div')
    ->toContain('text-primary-500')
    ->toContain('text-2xl')
    ->not->toContain('<a ')
    ->not->toContain('<svg');

it('can render as slot', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz">
        R$ 333,55
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('R$ 333,55')
        ->not->toContain('text-2xl');
});

it('can render using href', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="50" href="https://google.com.br" />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('https://google.com.br')
        ->toContain('50')
        ->toContain('<a ')
        ->toContain('cursor-pointer');
});

it('can render clickable with wire:click as div', function () {
    $component = <<<'HTML'
    <x-stats number="10" wire:click="refresh" />
    HTML;

    expect($component)
        ->render()
        ->toContain('wire:click="refresh"')
        ->toContain('cursor-pointer')
        ->toContain('<div')
        ->not->toContain('<a ');
});

it('can render title')
    ->expect('<x-stats title="FooBarBaz" number="333" />')
    ->render()
    ->toContain('333')
    ->toContain('FooBarBaz');

it('can render header')
    ->expect('<x-stats title="FooBarBaz" number="333" header="TallStackUI" />')
    ->render()
    ->toContain('FooBarBaz')
    ->toContain('333')
    ->toContain('TallStackUI')
    ->toContain('text-xs')
    ->toContain('mx-2');

it('can render header as slot', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="333">
        <x-slot:header>
            TallStackUI
        </x-slot:header>
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('333')
        ->toContain('TallStackUI')
        ->toContain('text-xs')
        ->toContain('mx-2');
});

it('can render footer')
    ->expect('<x-stats title="FooBarBaz" number="333" footer="TallStackUI" />')
    ->render()
    ->toContain('FooBarBaz')
    ->toContain('333')
    ->toContain('TallStackUI')
    ->toContain('text-xs')
    ->toContain('mx-2');

it('can render footer as slot', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="333">
        <x-slot:footer>
            TallStackUI
        </x-slot:footer>
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('333')
        ->toContain('TallStackUI')
        ->toContain('text-xs')
        ->toContain('mx-2');
});

it('can render using increase icon', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="10" increase />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('<svg')
        ->toContain('10');
});

it('can render using decrease icon', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="10" decrease />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('<svg')
        ->toContain('10');
});

it('cannot use increase and decrease together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [increase] and [decrease] cannot be used together.');

    expect('<x-stats number="10" increase decrease />')->render();
});

it('can render right slot', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" number="333">
        <x-slot:right>
            TallStackUI
        </x-slot:right>
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('333')
        ->toContain('TallStackUI');
});

it('can render number color from color prop', function () {
    expect('<x-stats number="333" color="red" />')
        ->render()
        ->toContain('333')
        ->toContain('text-red-500');
});

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-stats title="FooBarBaz" number="333" color="$colors" />
    HTML;

    expect($component)
        ->render()
        ->toContain('333')
        ->toContain('FooBarBaz');
})->with(colorsDataset());

it('can render the chart from the array shorthand')
    ->expect('<x-stats number="33" :chart="[10, 40, 25, 60]" />')
    ->render()
    ->toContain('33')
    ->toContain('<svg')
    ->toContain('relative')
    ->toContain('isolate')
    ->toContain('-z-10')
    ->toContain('min-height: 64px')
    ->not->toContain('min-height: 240px');

it('cannot change the shorthand chart height from the stats tag', function () {
    expect('<x-stats number="33" :chart="[10, 40, 25, 60]" :height="240" />')
        ->render()
        ->toContain('min-height: 64px')
        ->not->toContain('min-height: 240px')
        ->not->toContain('height="240"');
});

it('can render the chart inheriting the stats color')
    ->expect('<x-stats number="33" color="green" :chart="[10, 40, 25, 60]" />')
    ->render()
    ->toContain('text-green-500');

it('can render the chart from a collection')
    ->expect('<x-stats number="33" :chart="collect([10, 40, 25, 60])" />')
    ->render()
    ->toContain('<svg')
    ->toContain('isolate');

it('can render the chart as slot', function () {
    $component = <<<'HTML'
    <x-stats number="333">
        <x-slot:chart>
            <span>CustomChart</span>
        </x-slot:chart>
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('333')
        ->toContain('CustomChart')
        ->toContain('isolate');
});

it('cannot apply the chart layer without a chart', function (string $component) {
    expect($component)
        ->render()
        ->not->toContain('isolate')
        ->not->toContain('-z-10')
        ->not->toContain('<svg');
})->with([
    'absent' => ['<x-stats number="33" />'],
    'empty array' => ['<x-stats number="33" :chart="[]" />'],
    'empty slot' => ['<x-stats number="33"><x-slot:chart></x-slot:chart></x-stats>'],
]);

it('cannot use the chart prop and the chart slot together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Cannot pass both [:chart] and the [chart] slot simultaneously. Choose one.');

    $component = <<<'HTML'
    <x-stats number="1" :chart="[1, 2, 3]"><x-slot:chart><span>Foo</span></x-slot:chart></x-stats>
    HTML;

    expect($component)->render();
});

it('renders the chart before the animated number element', function () {
    // The count-up overwrites the whole textContent of [x-ref="number"], so
    // the chart must never end up inside that subtree.
    $html = expect('<x-stats number="1" animated :chart="[1, 2, 3]" />')->render()->value;

    expect(strpos($html, 'tallstackui_stats_chart'))->toBeLessThan(strpos($html, 'x-ref="number"'));
});

it('keeps the shadowless scope working alongside a chart')
    ->expect('<x-stats number="1" scope="stats-shadowless" :chart="[1, 2, 3]" />')
    ->render()
    ->toContain('border border-gray-200')
    ->toContain('isolate')
    ->not->toContain('shadow-md');

it('can render with shadow and without border by default')
    ->expect('<x-stats number="1" />')
    ->render()
    ->toContain('shadow-md')
    ->not->toContain('shadow-none!')
    ->not->toContain('border-gray-200');

it('can render shadowless')
    ->expect('<x-stats number="1" shadowless />')
    ->render()
    ->toContain('shadow-none!');

it('can render bordered')
    ->expect('<x-stats number="1" bordered />')
    ->render()
    ->toContain('border border-gray-200 dark:border-dark-700');

it('can render shadowless and bordered together')
    ->expect('<x-stats number="1" shadowless bordered />')
    ->render()
    ->toContain('shadow-none!')
    ->toContain('border border-gray-200 dark:border-dark-700');

it('can render the flat look through the global configuration', function () {
    config()->set('ts-ui.components.stats.1.shadowless', true);
    config()->set('ts-ui.components.stats.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-stats number="1" />')->render()
            ->toContain('shadow-none!')
            ->toContain('border border-gray-200');

        expect('<x-stats number="1" skeleton />')->render()
            ->toContain('shadow-none!')
            ->toContain('border border-gray-200');
    } finally {
        config()->set('ts-ui.components.stats.1.shadowless', false);
        config()->set('ts-ui.components.stats.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the flat look props win over the global configuration', function () {
    config()->set('ts-ui.components.stats.1.shadowless', true);
    config()->set('ts-ui.components.stats.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-stats number="1" :shadowless="false" :bordered="false" />')->render()
            ->not->toContain('shadow-none!')
            ->not->toContain('border border-gray-200');
    } finally {
        config()->set('ts-ui.components.stats.1.shadowless', false);
        config()->set('ts-ui.components.stats.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can render the skeleton instead of the content')
    ->expect('<x-stats skeleton number="1234" title="Users" />')
    ->render()
    ->toContain('animate-pulse')
    ->not->toContain('1234');

it('can render the skeleton with the icon, the title, the header and the footer', function () {
    $html = Blade::render('<x-stats skeleton icon="users" title="Users" header="Foo" footer="Bar" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(5);
});

it('cannot render the skeleton with a count', function () {
    $this->expectException(ViewException::class);

    expect('<x-stats skeleton="3" />')->render();
});
