<?php

uses(Tests\TestCase::class);

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

it('uses numeric inputmode for positive integers')
    ->expect('<x-number min="0" step="1" />')
    ->render()
    ->toContain('inputmode="numeric"')
    ->toContain('pattern="[0-9]*"');

it('uses decimal inputmode for positive decimals')
    ->expect('<x-number min="0" step="0.01" />')
    ->render()
    ->toContain('inputmode="decimal"')
    ->toContain('pattern="[0-9]*[.,]?[0-9]*"');

it('uses text inputmode when min is negative')
    ->expect('<x-number min="-100" step="1" />')
    ->render()
    ->toContain('inputmode="text"')
    ->toContain('pattern="-?[0-9]*[.,]?[0-9]*"');

it('uses text inputmode when min is not set')
    ->expect('<x-number step="1" />')
    ->render()
    ->toContain('inputmode="text"')
    ->toContain('pattern="-?[0-9]*[.,]?[0-9]*"');

it('uses decimal inputmode for decimals with positive min')
    ->expect('<x-number min="10" step="0.5" />')
    ->render()
    ->toContain('inputmode="decimal"')
    ->toContain('pattern="[0-9]*[.,]?[0-9]*"');
