<?php

use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;
use TasteUi\View\Components\Card;

test('customizable')
    ->expect(Card::class)
    ->toImplement(Customizable::class);

test('contains method')
    ->expect(Card::class)
    ->toHaveMethod('customization');

test('contains constructor')
    ->expect(Card::class)
    ->toHaveConstructor();

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

    TasteUi::personalization('taste-ui::personalizations.card')
        ->block('base', function () {
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

    TasteUi::personalization('taste-ui::personalizations.card')
        ->block('foo-bar', function () {
            return 'grow rounded-t-xl px-2 py-5 text-secondary-700 md:px-4';
        });

    $this->blade('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
        ->assertSee('rounded-t-xl')
        ->assertDontSee('rounded-b-xl');
});
