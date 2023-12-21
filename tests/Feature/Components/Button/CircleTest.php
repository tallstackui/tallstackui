<?php

it('can render with slot')
    ->expect('<x-button.circle>Foo bar</x-button.circle>')
    ->render()
    ->toContain('Foo bar');

it('can render with text')
    ->expect('<x-button.circle text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render round')
    ->expect('<x-button.circle text="Foo bar" round />')
    ->render()
    ->toContain('rounded-full');

it('can render with icon')
    ->expect('<x-button.circle text="Foo bar" icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render as tag a')
    ->expect('<x-button.circle href="https://google.com.br" target="_blank">Foo bar</x-button.circle>')
    ->render()
    ->toContain('<a  href="https://google.com.br"')
    ->toContain('_blank');

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button.circle text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'bg-white',
        'black' => 'bg-black',
        default => "bg-$colors-500",
    };

    expect($component)->render()
        ->toContain($color);
})->with('colors');

it('can render lg', function () {
    expect('<x-button.circle text="LG" color="primary" lg />')->render()
        ->toContain('w-12 h-12')
        ->toContain('text-lg');

    expect('<x-button.circle icon="users" color="primary" lg />')->render()
        ->toContain('w-12 h-12')
        ->toContain('w-6 h-6');
});

it('can render md', function () {
    expect('<x-button.circle text="MD" color="primary" />')->render()
        ->toContain('w-9 h-9')
        ->toContain('text-md');

    expect('<x-button.circle icon="users" color="primary" />')->render()
        ->toContain('w-9 h-9')
        ->toContain('w-4 h-4');
});

it('can render sm', function () {
    expect('<x-button.circle sm text="MD" color="primary" />')->render()
        ->toContain('w-6 h-6')
        ->toContain('text-sm');

    expect('<x-button.circle sm icon="users" color="primary" />')->render()
        ->toContain('w-6 h-6')
        ->toContain('w-3 h-3');
});

it('can render xs', function () {
    expect('<x-button.circle xs text="MD" color="primary" />')->render()
        ->toContain('w-4 h-4')
        ->toContain('text-xs');

    expect('<x-button.circle xs icon="users" color="primary" />')->render()
        ->toContain('w-4 h-4')
        ->toContain('w-2 h-2');
});
