<?php

it('can render with boolean true')
    ->expect('<x-boolean :boolean="true" />')
    ->render()
    ->toContain('<svg');

it('can render with boolean false')
    ->expect('<x-boolean :boolean="false" />')
    ->render()
    ->toContain('<svg');

it('can resolve using different aproaches', function () {
    expect('<x-boolean :boolean="true" />')
        ->render()
        ->toContain('<svg')
        ->and('<x-boolean :boolean="false" />')
        ->render()
        ->toContain('<svg')
        ->and('<x-boolean :boolean="fn () => true" />')
        ->render()
        ->toContain('<svg')
        ->and('<x-boolean :boolean="function () { return true; }" />')
        ->render()
        ->toContain('<svg')
        ->and('<x-boolean :boolean="1" />')
        ->render()
        ->toContain('<svg');
});

it('can render different icons', function () {
    $component = <<<'HTML'
    <x-boolean :boolean="true" 
               icon-when-true="hand-thumb-up"
               icon-when-false="hand-thumb-down" />
    HTML;

    expect($component)->render()->toContain('<svg');
});

it('can render different colors', function () {
    $component = <<<'HTML'
    <x-boolean :boolean="true" color-when-true="red" />
    HTML;

    expect($component)->render()->toContain('text-red-500');

    $component = <<<'HTML'
    <x-boolean :boolean="false" color-when-false="yellow" />
    HTML;

    expect($component)->render()->toContain('text-yellow-500');
});

it('can render clickable action', function () {
    $component = <<<'HTML'
    <x-boolean :boolean="true" wire:click="foo" />
    HTML;

    expect($component)
        ->render()
        ->toContain('wire:click="foo"');
});
