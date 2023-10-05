<?php

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

it('can render in top', function () {
    $this->blade('<x-tooltip text="Foo bar" position="top" />')
        ->assertSee('data-position="top"', false);
});

it('can render in bottom', function () {
    $this->blade('<x-tooltip text="Foo bar" position="bottom" />')
        ->assertSee('data-position="bottom"', false);
});

it('can render in right', function () {
    $this->blade('<x-tooltip text="Foo bar" position="right" />')
        ->assertSee('data-position="right"', false);
});

it('can render in left', function () {
    $this->blade('<x-tooltip text="Foo bar" position="left" />')
        ->assertSee('data-position="left"', false);
});
