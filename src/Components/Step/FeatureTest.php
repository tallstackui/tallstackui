<?php

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
        ->toContain('class="overflow-hidden mb-2 rounded-md border border-gray-300 dark:border-dark-700"')
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
