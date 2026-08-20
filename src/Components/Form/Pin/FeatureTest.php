<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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

it('can render with smart')
    ->expect('<x-pin length="4" smart />')
    ->render()
    ->toContain('true');

it('can render with password')
    ->expect('<x-pin length="4" password />')
    ->render()
    ->toContain('type="password"')
    ->not->toContain('type="text"');

it('can render with separator split in the middle')
    ->expect('<x-pin length="6" separator />')
    ->render()
    ->toContain('dusk="pin-separator-3"')
    ->toContain('aria-hidden="true">-</span>')
    ->not->toContain('dusk="pin-separator-2"')
    ->not->toContain('dusk="pin-separator-6"');

it('can render with custom separator')
    ->expect('<x-pin length="4" separator="/" />')
    ->render()
    ->toContain('aria-hidden="true">/</span>');

it('can render with separator at custom positions')
    ->expect('<x-pin length="6" separator split="2,4" />')
    ->render()
    ->toContain('dusk="pin-separator-2"')
    ->toContain('dusk="pin-separator-4"')
    ->not->toContain('dusk="pin-separator-3"');

it('can render with group')
    ->expect('<x-pin length="4" group />')
    ->render()
    ->toContain('rounded-l-md')
    ->toContain('rounded-r-md')
    ->toContain('-ml-px');

it('cannot use the pin without length', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin />')->render();
});

it('cannot use numbers and letters together', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin length="4" numbers letters />')->render();
});

it('cannot use separator longer than three characters', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin length="4" separator="----" />')->render();
});

it('cannot use split without separator', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin length="4" split="2" />')->render();
});

it('cannot use split outside the length', function () {
    $this->expectException(ViewException::class);

    expect('<x-pin length="4" separator split="4" />')->render();
});
