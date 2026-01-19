<?php

it('can render')
    ->expect('<x-stats number="33" />')
    ->render()
    ->toContain('33')
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
        ->toContain('R$ 333,55');
});

it('can render using href', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" href="https://google.com.br">
        R$ 333,55
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('https://google.com.br')
        ->toContain('R$ 333,55');
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
    ->toContain('TallStackUI');

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
        ->toContain('TallStackUI');
});

it('can render footer')
    ->expect('<x-stats title="FooBarBaz" number="333" footer="TallStackUI" />')
    ->render()
    ->toContain('FooBarBaz')
    ->toContain('333')
    ->toContain('TallStackUI');

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
        ->toContain('TallStackUI');
});

it('can render using increase icon', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" increase>
        R$ 333,55
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('<svg')
        ->toContain('R$ 333,55');
});

it('can render using decrease icon', function () {
    $component = <<<'HTML'
    <x-stats title="FooBarBaz" decrease>
        R$ 333,55
    </x-stats>
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBarBaz')
        ->toContain('<svg')
        ->toContain('R$ 333,55');
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

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-stats title="FooBarBaz" number="333" color="$colors" />
    HTML;

    expect($component)
        ->render()
        ->toContain('333')
        ->toContain('FooBarBaz');
})->with('colors');
