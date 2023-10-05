<?php

it('can render', function () {
    $this->blade('<x-badge text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500');
});

it('can render slot', function () {
    $this->blade('<x-badge>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500');
});

it('can render outline', function () {
    $this->blade('<x-badge outline>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertSee('border-primary-500 text-primary-500');
});

it('can render icon on left', function () {
    $this->blade('<x-badge icon="users" position="left" text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500')
        ->assertSee('mr-1');
});

it('can render icon on right', function () {
    $this->blade('<x-badge icon="users" position="right" text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500')
        ->assertSee('ml-1');
});
