<?php

it('can render')
    ->expect('<x-number />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-number label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-number label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');
