<?php

it('can render askerisk', function () {
    expect('<x-input label="FooBar *" hint="Insert your name" />')->render()
        ->toContain('font-bold not-italic text-red-500');
});

it('cannot render askerisk', function (string $label) {
    $component = <<<HTML
    <x-input label="$label" hint="Insert your name" />
    HTML;

    expect($component)->render()->not->toContain('<i class');
})->with(['FooBar', 'FooBar **']);
