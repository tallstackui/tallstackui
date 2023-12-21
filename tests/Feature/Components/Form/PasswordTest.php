<?php

it('can render')
    ->expect('<x-password />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-password label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-password label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');
