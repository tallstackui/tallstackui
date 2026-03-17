<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-color label="Foo bar" hint="Bar baz" />')->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('cannot render with excluded step without picker', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-step="500" />')->render();
});

it('cannot render with invalid excluded color', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-color="invalid" />')->render();
});

it('cannot render with invalid excluded step', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-step="999" />')->render();
});
