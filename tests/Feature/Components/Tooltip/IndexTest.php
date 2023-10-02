<?php

use TasteUi\Facades\TasteUi;

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

it('can personalize', function () {
    $this->blade('<x-tooltip text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('inline-flex');

    TasteUi::personalize('tooltip')
        ->block('wrapper', function () {
            return 'justify-center';
        });

    $this->blade('<x-tooltip text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('justify-center')
        ->assertDontSee('inline-flex');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    $this->blade('<x-tooltip text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('inline-flex');

    TasteUi::personalize('tooltip')
        ->block('foo-bar', function () {
            return 'justify-center';
        });

    $this->blade('<x-tooltip text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('justify-center')
        ->assertDontSee('inline-flex');
});
