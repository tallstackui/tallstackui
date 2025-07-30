<?php

it('can render')
    ->expect('<x-input />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-input label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-input label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with icon', function (string $position) {
    $component = <<<HTML
    <x-input label="Foo bar" hint="Bar baz" icon="cog" position="$position" />
    HTML;

    $position = match ($position) {
        'left' => 'pl-8',
        'right' => 'pr-8',
    };

    expect($component)->render()
        ->toContain('<input')
        ->toContain('Bar baz')
        ->toContain('Foo bar')
        ->toContain('<svg')
        ->toContain($position);
})->with(['left', 'right']);

it('can render with strip-leading-zeros')
    ->expect('<x-input strip-leading-zeros />')
    ->render()
    ->toContain('<input')
    ->toContain('x-data="tallstackui_formStripLeadingZeros');

it('can render with strip-leading-zeros and type number')
    ->expect('<x-input type="number" strip-leading-zeros />')
    ->render()
    ->toContain('<input')
    ->toContain('type="number"')
    ->toContain('x-data="tallstackui_formStripLeadingZeros');

it('can render with strip-leading-zeros and initial value')
    ->expect('<x-input strip-leading-zeros value="0001" />')
    ->render()
    ->toContain('<input')
    ->toContain('value="0001"')
    ->toContain('x-data="tallstackui_formStripLeadingZeros');
