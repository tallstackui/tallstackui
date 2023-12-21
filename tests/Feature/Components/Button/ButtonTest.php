<?php

it('can render with slot')
    ->expect('<x-button>Foo bar</x-button>')
    ->render()
    ->toContain('Foo bar');

it('can render with text')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render xs')
    ->expect('<x-button text="Foo bar" xs />')
    ->render()
    ->toContain('px-1 py-0.5');

it('can render sm')
    ->expect('<x-button text="Foo bar" sm />')
    ->render()
    ->toContain('px-2 py-1');

it('can render md')
    ->expect('<x-button text="Foo bar" md />')
    ->render()
    ->toContain('px-4 py-2');

it('can render lg')
    ->expect('<x-button text="Foo bar" lg />')
    ->render()
    ->toContain('px-6 py-3');

it('can render square')
    ->expect('<x-button text="Foo bar" square />')
    ->render()
    ->not->toContain('rounded');

it('can render round')
    ->expect('<x-button text="Foo bar" round />')
    ->render()
    ->toContain('rounded-full');

it('can render as tag a')
    ->expect('<x-button href="https://google.com.br" text="Foo bar" round />')->render()
    ->toContain('<a  href="https://google.com.br"')
    ->not->toContain('<button');

it('can render with icon')
    ->expect('<x-button text="Foo bar" icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'bg-white',
        'black' => 'bg-black',
        default => "bg-$colors-500",
    };

    expect($component)->render()
        ->toContain($color);
})->with('colors');
