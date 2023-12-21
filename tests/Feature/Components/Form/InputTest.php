<?php

it('can render')
    ->expect('<x-input />')
    ->render()
    ->toContain('<input', false);

it('can render with label')
    ->expect('<x-input label="Foo bar" />')
    ->render()
    ->toContain('<input', false)
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-input label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with icon', function (string $position) {
    $component = <<<HTML
    <x-input label="Foo bar" hint="Bar baz" icon="cog" position="$position" />
    HTML;

    $position = match ($position) {
        'left' => 'pl-10',
        'right' => 'pr-10',
    };

    expect($component)->render()
        ->toContain('<input', false)
        ->toContain('Bar baz')
        ->toContain('Foo bar')
        ->toContain('<svg', false)
        ->toContain($position);
})->with(['left', 'right']);
