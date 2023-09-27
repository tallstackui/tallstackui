<?php

use TasteUi\Contracts\Customizable;
use TasteUi\View\Components\Button\Circle;

test('customizable')
    ->expect(Circle::class)
    ->toImplement(Customizable::class);

test('contains method')
    ->expect(Circle::class)
    ->toHaveMethod('customization');

test('contains constructor')
    ->expect(Circle::class)
    ->toHaveConstructor();

it('can render with slot', function () {
    $this->blade('<x-button.circle>Foo bar</x-button.circle>')
        ->assertSee('Foo bar');
});

it('can render with text', function () {
    $this->blade('<x-button.circle text="Foo bar" />')
        ->assertSee('Foo bar');
});

it('can render round', function () {
    $this->blade('<x-button.circle text="Foo bar" round />')
        ->assertSee('rounded-full');
});

it('can render with icon', function () {
    $this->blade('<x-button.circle text="Foo bar" icon="users" />')
        ->assertSee('<svg', false);
});

it('can render colored', function () {
    $this->blade('<x-button.circle text="Foo bar" color="primary" />')->assertSee('bg-primary-500');
    $this->blade('<x-button.circle text="Foo bar" color="red" />')->assertSee('bg-red-500');
    $this->blade('<x-button.circle text="Foo bar" color="yellow" />')->assertSee('bg-yellow-500');
    $this->blade('<x-button.circle text="Foo bar" color="blue" />')->assertSee('bg-blue-500');
});
