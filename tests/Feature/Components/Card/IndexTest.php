<?php

it('can render', function () {
    $this->blade('<x-card>Foo bar</x-card>')
        ->assertSee('Foo bar');
});

it('can render with title', function () {
    $this->blade('<x-card title="Bar Baz">Foo bar</x-card>')
        ->assertSee('Foo bar')
        ->assertSee('Bar Baz');
});

it('can render with footer', function () {
    $this->blade('<x-card footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('Foo bar')
        ->assertSee('Bar Baz');
});

it('can render with title and footer', function () {
    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('Foo bar')
        ->assertSee('Lorem Ipsum')
        ->assertSee('Bar Baz');
});
