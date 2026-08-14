<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('keeps the default floating floor when floating is not defined', function () {
    $component = <<<'HTML'
    <x-input.select label="Email">
        <x-slot:right>
            <x-select.styled :options="['@gmail.com', '@yahoo.com']" />
        </x-slot:right>
    </x-input.select>
HTML;

    expect($component)->render()
        ->toContain('min-w-72');
});

it('replaces the floating floor when floating is defined', function () {
    $component = <<<'HTML'
    <x-input.select label="Email" floating="min-w-40">
        <x-slot:right>
            <x-select.styled :options="['@gmail.com', '@yahoo.com']" />
        </x-slot:right>
    </x-input.select>
HTML;

    expect($component)->render()
        ->toContain('min-w-40')
        ->not->toContain('min-w-72');
});

it('replaces the floating floor on the left slot', function () {
    $component = <<<'HTML'
    <x-input.select label="Phone" floating="min-w-40">
        <x-slot:left>
            <x-select.styled :options="['+55', '+1']" />
        </x-slot:left>
    </x-input.select>
HTML;

    expect($component)->render()
        ->toContain('min-w-40')
        ->not->toContain('min-w-72');
});

it('does not put a select in side mode inside a foreign left/right slot', function () {
    $component = <<<'HTML'
    <x-layout.header>
        <x-slot:right>
            <x-select.styled label="Idioma" :options="[]" />
        </x-slot:right>
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('Idioma')
        ->not->toContain('ring-0!');
});

it('still puts a select in side mode inside the input.select slot', function () {
    $component = <<<'HTML'
    <x-input.select name="phone">
        <x-slot:left>
            <x-select.styled name="code" :options="[]" />
        </x-slot:left>
    </x-input.select>
    HTML;

    expect($component)->render()->toContain('ring-0!');
});
