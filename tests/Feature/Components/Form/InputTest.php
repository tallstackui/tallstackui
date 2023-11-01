<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-input />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false);
});

it('can render with label', function () {
    $component = <<<'HTML'
    <x-input label="Foo bar" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Foo bar');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-input label="Foo bar" hint="Bar baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar');
});

it('can render with icon', function (string $position) {
    $component = <<<HTML
    <x-input label="Foo bar" hint="Bar baz" icon="cog" position="$position" />
    HTML;

    $position = match ($position) {
        'left' => 'pl-10',
        'right' => 'pr-10',
    };

    $this->blade($component)
        ->assertSee('<input', false)
        ->assertSee('Bar baz')
        ->assertSee('Foo bar')
        ->assertSee('<svg', false)
        ->assertSee($position);
})->with(['left', 'right']);
