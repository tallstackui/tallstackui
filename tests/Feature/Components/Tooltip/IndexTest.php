<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $this->blade('<x-tooltip text="Foo bar" />')
        ->assertSee('h-5 w-5');
});

it('can render md', function () {
    $this->blade('<x-tooltip text="Foo bar" md />')
        ->assertSee('h-6 w-6');
});

it('can render lg', function () {
    $this->blade('<x-tooltip text="Foo bar" lg />')
        ->assertSee('h-7 w-7');
});

it('can render positioned', function (string $position) {
    $component = <<<HTML
    <x-tooltip text="Foo bar" position="$position" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo bar');
})->with(['top', 'bottom', 'left', 'right']);

it('cannot use bad positions', function (string $position) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The tooltip position must be one of the following: [top, bottom, right, left]');

    $component = <<<HTML
    <x-tooltip text="Foo bar" position="$position" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo bar');
})->with(['top-left', 'bottom-left', 'left-top', 'right-bottom']);
