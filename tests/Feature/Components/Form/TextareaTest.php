<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-textarea />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false);
});

it('can render with label', function () {
    $component = <<<'HTML'
    <x-textarea label="Foo bar" />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false)
        ->assertSee('Foo bar');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-textarea label="Foo bar" hint="Bar baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render without resize', function () {
    $component = <<<'HTML'
    <x-textarea label="Foo bar" hint="Bar baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false)
        ->assertSee('resize-none')
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render resize', function () {
    $component = <<<'HTML'
    <x-textarea label="Foo bar" hint="Bar baz" resize />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false)
        ->assertDontSee('resize-none')
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render with resize-auto', function () {
    $component = <<<'HTML'
    <x-textarea label="Foo bar" hint="Bar baz" resize-auto />
    HTML;

    $this->blade($component)
        ->assertSee('<textarea', false)
        ->assertDontSee('resize-none')
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});
