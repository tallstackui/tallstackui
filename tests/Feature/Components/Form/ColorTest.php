<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-color />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false);
});

it('can render in picker mode', function () {
    $component = <<<'HTML'
    <x-color picker />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false);
});

it('can render with custom colors', function () {
    $component = <<<'HTML'
    <x-color :colors="['#fff', '#000']" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('#fff', false)
        ->assertSee('#000', false);
});

it('can render with label', function () {
    $component = <<<'HTML'
    <x-color label="Foo bar" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-color label="Foo bar" hint="Bar baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar')
        ->assertSee('Bar baz');
});
