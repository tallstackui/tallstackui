<?php

it('can render title', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');
});

it('can render text', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300');
});

it('can render slot', function () {
    $this->blade('<x-alert>Foo bar</x-alert>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');
});

it('can render closeable alert', function () {
    $this->blade('<x-alert text="Foo bar" closeable />')
        ->assertSee('<svg class="w-5 h-5 text-primary-900"', false);
});

it('can render flat', function () {
    $this->blade('<x-alert text="Foo bar" flat />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300')
        ->assertSee('bg-primary-100');
});

it('can render black background with white text', function () {
    $this->blade('<x-alert text="Foo bar" color="black" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-black')
        ->assertSee('text-white')
        ->assertDontSee('text-black');
});

it('can render white background with black text', function () {
    $this->blade('<x-alert text="Foo bar" color="white" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white')
        ->assertSee('text-black')
        ->assertDontSee('text-white');
});

test('when flat and white, it does not render flat', function () {
    $this->blade('<x-alert text="Foo bar" color="white" flat />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white')
        ->assertSee('border')
        ->assertSee('border-gray-100');
});
