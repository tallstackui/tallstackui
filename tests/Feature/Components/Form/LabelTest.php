<?php

it('can render askerisk', function () {
    $component = <<<HTML
    <x-input label="FooBar *" hint="Insert your name" />
    HTML;

    $this->blade($component)
        ->assertSee('<i class', false);
});


it('cannot render askerisk', function (string $label) {
    $component = <<<HTML
    <x-input label="$label" hint="Insert your name" />
    HTML;

    $this->blade($component)
        ->assertDontSee('<i class', false);
})->with(['FooBar', 'FooBar **']);

