<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-pin length="2" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false);
});

it('can render with label', function () {
    $component = <<<'HTML'
    <x-pin label="Foo bar" length="2" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-pin label="Foo bar" hint="Bar baz" length="4" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar')
        ->assertSee('Bar baz');
});
