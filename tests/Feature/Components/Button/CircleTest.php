<?php

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

it('can render as tag a', function () {
    $this->blade('<x-button.circle href="https://google.com.br" target="_blank">Foo bar</x-button.circle>')
        ->assertSee('https://google.com.br')
        ->assertSee('<a', false)
        ->assertSee('_blank');
});

it('can render colored', function () {
    $this->blade('<x-button.circle text="Foo bar" color="primary" />')->assertSee('bg-primary-500');
    $this->blade('<x-button.circle text="Foo bar" color="red" />')->assertSee('bg-red-500');
    $this->blade('<x-button.circle text="Foo bar" color="yellow" />')->assertSee('bg-yellow-500');
    $this->blade('<x-button.circle text="Foo bar" color="blue" />')->assertSee('bg-blue-500');
});

it('can render large size as default', function () {
    $this->blade('<x-button.circle text="LG" color="primary" />')
        ->assertSee('w-12 h-12')
        ->assertSee('text-2xl');

    $this->blade('<x-button.circle icon="users" color="primary" />')
        ->assertSee('w-12 h-12')
        ->assertSee('w-8 h-8');
});

it('can render medium size', function () {
    $this->blade('<x-button.circle size="md" text="MD" color="primary" />')
        ->assertSee('w-9 h-9')
        ->assertSee('text-base');

    $this->blade('<x-button.circle size="md" icon="users" color="primary" />')
        ->assertSee('w-9 h-9')
        ->assertSee('w-4 h-4');
});

it('can render small size', function () {
    $this->blade('<x-button.circle size="sm" text="MD" color="primary" />')
        ->assertSee('w-6 h-6')
        ->assertSee('text-xs');

    $this->blade('<x-button.circle size="sm" icon="users" color="primary" />')
        ->assertSee('w-6 h-6')
        ->assertSee('w-3 h-3');
});
