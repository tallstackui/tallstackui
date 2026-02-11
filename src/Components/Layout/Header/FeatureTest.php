<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-layout.header>
        Foo bar
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
});

it('can render with left slot', function () {
    $component = <<<'HTML'
    <x-layout.header>
        <x-slot:left>
            LeftContent
        </x-slot:left>
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('LeftContent');
});

it('can render with right slot', function () {
    $component = <<<'HTML'
    <x-layout.header>
        <x-slot:right>
            RightContent
        </x-slot:right>
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('RightContent');
});
