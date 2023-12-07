<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-range />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false);
});

it('can render with label', function () {
    $component = <<<'HTML'
    <x-range label="Foo bar" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-range label="Foo bar" hint="Bar baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render as sm', function () {
    $component = <<<'HTML'
    <x-range label="Foo bar" hint="Bar baz" sm />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render as lg', function () {
    $component = <<<'HTML'
    <x-range label="Foo bar" hint="Bar baz" lg />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-range label="Foo bar" hint="Bar baz" color="$colors" />
    HTML;

    $colors = match ($colors) {
        'white' => 'white',
        'black' => 'black',
        default => $colors.'-500'
    };

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee($colors, false);
})->with('colors');
