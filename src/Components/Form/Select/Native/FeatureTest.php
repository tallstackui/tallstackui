<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-select.native />')
    ->render()
    ->toContain('<select');

it('can render with label')
    ->expect('<x-select.native label="Choose one" />')
    ->render()
    ->toContain('<select')
    ->toContain('Choose one');

it('can render with hint')
    ->expect('<x-select.native label="Choose one" hint="Pick your favorite" />')
    ->render()
    ->toContain('<select')
    ->toContain('Choose one')
    ->toContain('Pick your favorite');

it('can render with simple array options', function () {
    $component = <<<'HTML'
    <x-select.native :options="['Foo', 'Bar', 'Baz']" />
    HTML;

    expect($component)->render()
        ->toContain('<select')
        ->toContain('<option')
        ->toContain('Foo')
        ->toContain('Bar')
        ->toContain('Baz');
});

it('can render with selectable array options', function () {
    $component = <<<'HTML'
    <x-select.native :options="[['label' => 'Apple', 'value' => '1'], ['label' => 'Banana', 'value' => '2']]" select="label:label|value:value" />
    HTML;

    expect($component)->render()
        ->toContain('<select')
        ->toContain('<option')
        ->toContain('Apple')
        ->toContain('Banana');
});
