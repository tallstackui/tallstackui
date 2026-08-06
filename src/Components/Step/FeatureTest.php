<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo">
            Foo
        </x-step.items>
        <x-step.items step="2" title="Bar">
            Bar
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain('Foo', 'Bar');
});

it('can render with id on step items', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo" id="step-one">
            Foo
        </x-step.items>
        <x-step.items step="2" title="Bar" id="step-two">
            Bar
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain("id: 'step-one'")
        ->toContain("id: 'step-two'")
        ->toContain("x-bind:id=\"item.id ? 'li-' + item.id : null\"");
});

it('can keep the panels scroll container free of the border and the radius', function () {
    $component = <<<'HTML'
    <x-step selected="1" panels>
        <x-step.items step="1" title="Foo">
            Foo
        </x-step.items>
    </x-step>
    HTML;

    // The rounded border lives on the <nav> so its overflow-hidden clips the
    // horizontal scrollbar along the corner. A border or a radius on the <ul>
    // itself puts the scrollbar outside that clip and squares off its tips.
    expect($component)->render()
        ->toContain('class="overflow-hidden mb-2 rounded-md border border-gray-200 dark:border-dark-600"')
        ->toContain('class="md:flex overflow-auto soft-scrollbar"');
});

it('can render without id on step items', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo">
            Foo
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain('id: null');
});

it('can render the skeleton instead of the content')
    ->expect('<x-step skeleton><x-step.items step="1" title="Account">Real content</x-step.items></x-step>')
    ->render()
    ->toContain('animate-pulse')
    ->not->toContain('Real content');

it('can render the skeleton with the default step count', function () {
    $html = Blade::render('<x-step skeleton />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(10);
});

it('can render the skeleton with a custom step count', function () {
    $html = Blade::render('<x-step skeleton="5" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(16);
});

it('can render the skeleton in the circles variation')
    ->expect('<x-step skeleton circles />')
    ->render()
    ->toContain('rounded-full');

it('can render the skeleton in the panels variation')
    ->expect('<x-step skeleton panels />')
    ->render()
    ->toContain('size-10 rounded-full');

it('can render the skeleton with the helpers', function () {
    $html = Blade::render('<x-step skeleton="1" helpers navigate-previous />');

    expect(substr_count($html, 'h-10 w-24'))->toBe(2);
});

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-step skeleton="0" />')->render();
});
