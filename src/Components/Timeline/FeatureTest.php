<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('renders vertical by default', function () {
    $component = <<<'HTML'
    <x-timeline>
        <x-timeline.items title="First" />
        <x-timeline.items title="Second" />
    </x-timeline>
    HTML;

    expect($component)->render()
        ->toContain('First')
        ->toContain('Second')
        ->toContain('flex-col');
});

it('renders horizontal when horizontal prop is set', function () {
    $component = <<<'HTML'
    <x-timeline horizontal>
        <x-timeline.items title="A" />
        <x-timeline.items title="B" />
    </x-timeline>
    HTML;

    expect($component)->render()
        ->toContain('flex-row');
});

it('renders items from array', function () {
    $items = [
        ['title' => 'v1.0', 'date' => 'Jan'],
        ['title' => 'v2.0', 'date' => 'Feb'],
    ];

    $component = '<x-timeline :items="$items" />';

    expect($component)->render(['items' => $items])
        ->toContain('v1.0')
        ->toContain('v2.0')
        ->toContain('Jan')
        ->toContain('Feb');
});

it('renders items via slot', function () {
    $component = <<<'HTML'
    <x-timeline>
        <x-timeline.items title="Slot1" />
    </x-timeline>
    HTML;

    expect($component)->render()->toContain('Slot1');
});

it('alternates item sides when alternate prop is set', function () {
    $items = [
        ['title' => 'One'],
        ['title' => 'Two'],
    ];

    $component = '<x-timeline :items="$items" alternate />';

    expect($component)->render(['items' => $items])
        ->toContain('col-start-3')
        ->toContain('col-start-1');
});

it('inherits horizontal from the parent when items come from the slot', function () {
    $component = <<<'HTML'
    <x-timeline horizontal>
        <x-timeline.items title="A" />
        <x-timeline.items title="B" />
    </x-timeline>
    HTML;

    // The horizontal item draws a line on each side; the vertical one draws a single line.
    expect($component)->render()
        ->toContain('data-timeline-line-left')
        ->toContain('data-timeline-line-right');
});

it('inherits alternate and compact from the parent when items come from the slot', function () {
    $component = <<<'HTML'
    <x-timeline alternate compact>
        <x-timeline.items title="A" />
    </x-timeline>
    HTML;

    expect($component)->render()
        ->toContain('grid-cols-[1fr_auto_1fr]')
        ->toContain('bottom-0');
});

it('inherits color from the parent when items come from the slot', function () {
    $component = <<<'HTML'
    <x-timeline color="red">
        <x-timeline.items title="A" />
    </x-timeline>
    HTML;

    expect($component)->render()
        ->toContain('red')
        ->not->toContain('primary');
});

it('does not inherit color from an ancestor other than the timeline', function () {
    $component = <<<'HTML'
    <x-card color="red">
        <x-timeline>
            <x-timeline.items title="A" />
        </x-timeline>
    </x-card>
    HTML;

    expect($component)->render()->toContain('primary');
});

it('lets the item override the color inherited from the parent', function () {
    $component = <<<'HTML'
    <x-timeline color="red">
        <x-timeline.items title="A" color="blue" />
    </x-timeline>
    HTML;

    expect($component)->render()->toContain('blue');
});

it('throws when both items and slot are provided', function () {
    $this->expectException(ViewException::class);

    $items = [['title' => 'X']];

    expect('<x-timeline :items="$items"><x-timeline.items title="Slot" /></x-timeline>')
        ->render(['items' => $items]);
});

it('throws when style is invalid', function () {
    $this->expectException(ViewException::class);

    expect('<x-timeline style="wild"><x-timeline.items title="A" /></x-timeline>')->render();
});
