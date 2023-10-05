<?php

use Illuminate\View\ViewException;

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

it('can render translucent', function () {
    $this->blade('<x-alert text="Foo bar" translucent />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300')
        ->assertSee('bg-primary-100');
});

it('can render outline', function () {
    $this->blade('<x-alert text="Foo bar" outline />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300')
        ->assertSee('bg-primary-100')
        ->assertSee('border')
        ->assertSee('border-primary-900');
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

test('when translucent and white, it does not render translucent', function () {
    $this->blade('<x-alert text="Foo bar" color="white" translucent />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white');
});

test('when outline and white, it does not render outline', function () {
    $this->blade('<x-alert text="Foo bar" color="white" outline />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white');
});

it('can not render outline and translucent on the same component', function () {
    $this->blade('<x-alert text="Foo bar" outline translucent />');
})->throws(ViewException::class);
