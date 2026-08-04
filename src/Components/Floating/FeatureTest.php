<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-floating>
        Foo bar
    </x-floating>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('x-anchor.bottom-end.offset.10');
});

it('resolves an auto position into a concrete placement', function (string $position, string $expected) {
    expect("<x-floating position=\"{$position}\">Foo bar</x-floating>")->render()
        ->toContain("x-anchor.{$expected}.offset.10");
})->with([
    ['auto', 'bottom'],
    ['auto-start', 'bottom-start'],
    ['auto-end', 'bottom-end'],
]);

it('keeps a concrete position untouched', function () {
    expect('<x-floating position="top-start">Foo bar</x-floating>')->render()
        ->toContain('x-anchor.top-start.offset.10');
});
