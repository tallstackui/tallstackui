<?php

use Illuminate\View\ViewException;

it('can render using left slot', function () {
    $component = <<<HTML
    <x-input.selector label="Foo bar" hint="Bar baz">
        <x-slot:left>
            <x-select.native :options="[1,2,3]" />       
        </x-slot:left>
    </x-input.selector>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('<select');
});

it('can render using right slot', function () {
    $component = <<<HTML
    <x-input.selector label="Foo bar" hint="Bar baz">
        <x-slot:right>
            <x-select.native :options="[1,2,3]" />       
        </x-slot:right>
    </x-input.selector>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('<select');
});

it('can throw exception when trying to render without slots', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage("[TallStackUI] Form\InputSelector: You must provide a [left] or [right] slot with a select component.");

    $component = <<<HTML
    <x-input.selector label="Foo bar" hint="Bar baz" />
    HTML;

    expect($component)->render()->toContain('<input');
});
