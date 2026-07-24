<?php

use Illuminate\View\ViewException;
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
