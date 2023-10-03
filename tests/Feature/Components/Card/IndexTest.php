<?php

use TasteUi\Facades\TasteUi;

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

it('can personalize', function () {
    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('rounded-b-xl');

    TasteUi::personalize()
        ->card()
        ->base(function () {
            return 'grow rounded-t-xl px-2 py-5 text-secondary-700 md:px-4';
        });

    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('rounded-t-xl')
        ->assertDontSee('rounded-b-xl');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('rounded-b-xl');

    TasteUi::personalize()
        ->card()
        ->wrong('foo-bar', function () {
            return 'grow rounded-t-xl px-2 py-5 text-secondary-700 md:px-4';
        });

    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('rounded-t-xl')
        ->assertDontSee('rounded-b-xl');
});