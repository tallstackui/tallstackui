<?php

use TasteUi\Facades\TasteUi;

it('can render with slot', function () {
    $this->blade('<x-button>Foo bar</x-button>')
        ->assertSee('Foo bar');
});

it('can render with text', function () {
    $this->blade('<x-button text="Foo bar" />')
        ->assertSee('Foo bar');
});

it('can render xs', function () {
    $this->blade('<x-button text="Foo bar" xs />')
        ->assertSee('px-1 py-0.5');
});

it('can render sm', function () {
    $this->blade('<x-button text="Foo bar" sm />')
        ->assertSee('px-2 py-1');
});

it('can render md', function () {
    $this->blade('<x-button text="Foo bar" md />')
        ->assertSee('px-4 py-2');
});

it('can render lg', function () {
    $this->blade('<x-button text="Foo bar" lg />')
        ->assertSee('px-6 py-3');
});

it('can render square', function () {
    $this->blade('<x-button text="Foo bar" square />')
        ->assertDontSee('rounded');
});

it('can render round', function () {
    $this->blade('<x-button text="Foo bar" round />')
        ->assertSee('rounded-full');
});

it('can render as tag a', function () {
    $this->blade('<x-button href="https://google.com.br" text="Foo bar" round />')
        ->assertSee('<a', false)
        ->assertDontSee('<button', false);
});

it('can render with icon', function () {
    $this->blade('<x-button text="Foo bar" icon="users" />')
        ->assertSee('<svg', false);
});

it('can render colored', function () {
    $this->blade('<x-button text="Foo bar" color="primary" />')->assertSee('bg-primary-500');
    $this->blade('<x-button text="Foo bar" color="red" />')->assertSee('bg-red-500');
    $this->blade('<x-button text="Foo bar" color="yellow" />')->assertSee('bg-yellow-500');
    $this->blade('<x-button text="Foo bar" color="blue" />')->assertSee('bg-blue-500');
});

it('can personalize', function () {
    $this->blade('<x-button text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500')
        ->assertDontSee('rounded-md');

    TasteUi::personalize()
        ->button()
        ->wrapper(fn() => 'rounded-md');

    $this->blade('<x-button text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('rounded-md');
});

it('can personalize all blocks available', function () {
    $this->blade('<x-button icon="user" />')
        ->assertSee('bg-primary-500')
        ->assertDontSee('rounded-md');

    TasteUi::personalize()
        ->button()
        ->wrapper(fn() => 'rounded-md')
        ->icon('w-4 h-4');

    $this->blade('<x-button icon="user" />')
        ->assertSee('rounded-md')
        ->assertSee('w-4 h-4');
});