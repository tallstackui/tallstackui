<?php

it('can render')
    ->expect('<x-color />')
    ->render()
    ->toContain('<input');

it('can render in picker mode')
    ->expect('<x-color picker />')
    ->render()
    ->toContain('<input');

it('can render with custom colors', function () {
    $component = <<<'HTML'
    <x-color :colors="['#fff', '#000']" />
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('#fff')
        ->toContain('#000');
});

it('can render with label')
    ->expect('<x-color label="Foo bar" />')
    ->render()
    ->toContain('<input', false)
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-color label="Foo bar" hint="Bar baz" />')->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');
