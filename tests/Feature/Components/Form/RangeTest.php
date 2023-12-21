<?php

it('can render')
    ->expect('<x-range />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-range label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-range label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with sizes', function (string $size) {
    $component = <<<'HTML'
    <x-range label="Foo bar" hint="Bar baz" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $size, $component);

    expect($component)->render()
        ->toContain('<input')
        ->toContain('Bar baz')
        ->toContain('Foo bar');
})->with(['sm', 'md', 'lg']);

it('can render with steps')
    ->expect('<x-range label="Foo bar" hint="Bar baz" step="5" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-range label="Foo bar" hint="Bar baz" color="$colors" />
    HTML;

    $colors = match ($colors) {
        'white' => 'white',
        'black' => 'black',
        default => $colors.'-500'
    };

    expect($component)->render()
        ->toContain('<input')
        ->toContain($colors);
})->with('colors');
