<?php

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-pin length="2" />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-pin label="Foo bar" length="2" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-pin label="Foo bar" hint="Bar baz" length="4" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('cannot use the pin without length', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin />')->render();
});
