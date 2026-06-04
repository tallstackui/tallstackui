<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-dial><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('tallstackui_dial_toggle')
    ->toContain('tallstackui_dial_item');

it('renders the item with the neutral default when no color is set')
    ->expect('<x-dial><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('bg-white');

it('renders the item with a solid color')
    ->expect('<x-dial><x-dial.items icon="pencil" color="red" /></x-dial>')
    ->render()
    ->toContain('bg-red-500')
    ->not->toContain('bg-white');

it('renders the item with a light color')
    ->expect('<x-dial><x-dial.items icon="pencil" color="blue" style="light" /></x-dial>')
    ->render()
    ->toContain('bg-blue-300');

it('renders the item with an outline color')
    ->expect('<x-dial><x-dial.items icon="pencil" color="green" style="outline" /></x-dial>')
    ->render()
    ->toContain('border-green-600');

it('applies the color to the item icon')
    ->expect('<x-dial><x-dial.items icon="pencil" color="red" style="light" /></x-dial>')
    ->render()
    ->toContain('dark:text-red-500');

it('renders items with individual colors', function () {
    $component = <<<'HTML'
    <x-dial>
        <x-dial.items icon="pencil" color="red" />
        <x-dial.items icon="trash" color="green" />
    </x-dial>
    HTML;

    expect($component)->render()
        ->toContain('bg-red-500')
        ->toContain('bg-green-500');
});

it('closes the dial when an item is clicked')
    ->expect('<x-dial><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('x-on:click="show = false"');

it('animates upwards when positioned at the bottom')
    ->expect('<x-dial position="bottom-right"><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('opacity-0 translate-y-3')
    ->toContain('opacity-100 translate-y-0');

it('animates downwards when positioned at the top')
    ->expect('<x-dial position="top-left"><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('opacity-0 -translate-y-3')
    ->toContain('opacity-100 translate-y-0');

it('animates to the left when horizontal and anchored to the right')
    ->expect('<x-dial horizontal position="bottom-right"><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('opacity-0 translate-x-3')
    ->toContain('opacity-100 translate-x-0');

it('animates to the right when horizontal and anchored to the left')
    ->expect('<x-dial horizontal position="bottom-left"><x-dial.items icon="pencil" /></x-dial>')
    ->render()
    ->toContain('opacity-0 -translate-x-3')
    ->toContain('opacity-100 translate-x-0');
