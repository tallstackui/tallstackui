<?php

it('can render')
    ->expect('<x-tag />')
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
