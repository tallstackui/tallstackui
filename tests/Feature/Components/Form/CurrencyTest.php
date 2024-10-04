<?php

it('can render')
    ->expect('<x-currency />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-currency label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-currency label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with prefix', function () {
    $component = <<<HTML
    <x-currency prefix="£" />
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('£');
});
