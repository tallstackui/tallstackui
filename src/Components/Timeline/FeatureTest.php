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
