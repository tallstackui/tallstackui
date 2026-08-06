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
