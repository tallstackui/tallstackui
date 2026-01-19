<?php

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-tag />')
    ->render()
    ->toContain('<input');

it('can render with prefix')
    ->expect('<x-tag prefix="@" />')
    ->render()
    ->toContain('<input');

it('can render with label and hint')
    ->expect('<x-tag label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('can render with tags', function () {
    $component = <<<'HTML'
    <x-tag :value="['foo', 'bar', 'baz']" />
    HTML;

    expect($component)->render()
        ->toContain('foo', 'bar', 'baz');
});

it('cannot render with prefix with two strings', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-tag prefix="@@" />
    HTML;

    expect($component)->render();
});
