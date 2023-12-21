<?php

it('can render')
    ->expect('<x-textarea />')
    ->render()
    ->toContain('<textarea');

it('can render with label')
    ->expect('<x-textarea label="Foo bar" />')
    ->render()
    ->toContain('<textarea')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-textarea label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<textarea')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render without resize')
    ->expect('<x-textarea label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<textarea')
    ->toContain('resize-none')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render resize')
    ->expect('<x-textarea label="Foo bar" hint="Bar baz" resize />')
    ->render()
    ->toContain('<textarea')
    ->toContain('Bar baz')
    ->toContain('Foo bar')
    ->not->toContain('resize-none');

it('can render with resize-auto')
    ->expect('<x-textarea label="Foo bar" hint="Bar baz" resize-auto />')
    ->render()
    ->toContain('<textarea')
    ->toContain('Bar baz')
    ->toContain('Foo bar')
    ->not->toContain('resize-none');
