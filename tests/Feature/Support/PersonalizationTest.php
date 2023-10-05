<?php

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;

it('can be instantiated', function () {
    expect(TasteUi::personalize())->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component', function () {
    expect(TasteUi::personalize('alert')
        ->block(['base' => fn () => 'string']))
        ->toBeInstanceOf(Personalizable::class);
});

it('can instantiate all components', function (string $component) {
    expect(TasteUi::personalize($component)->instance())->toBeInstanceOf(Personalizable::class);
})->with('personalizations.keys');

it('can instanciate all components extends of resource', function (string $component) {
    expect(new $component)->toBeInstanceOf(PersonalizationResource::class);
})->with('personalizations.classes');

it('can personalize using facade and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TasteUi::personalize('alert')
        ->block('base', 'rounded-md p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300');
});

it('can personalize using method and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TasteUi::personalize()
        ->alert()
        ->block('base', 'rounded-md p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300');
});

it('can personalize using method and closure', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TasteUi::personalize()
        ->alert()
        ->block('base', fn () => 'rounded-md p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300');
});

it('can personalize using method and array', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TasteUi::personalize()
        ->alert()
        ->block([
            'base' => 'rounded-md p-4',
        ]);

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300');
});

it('can personalize in sequenece', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('w-12 h-12');

    TasteUi::personalize()
        ->alert()
        ->block('base', 'rounded-md p-4')
        ->and()
        ->avatar()
        ->block('wrapper', 'inline-flex shrink-0 items-center justify-center overflow-hidden text-xl w-20 h-20');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('Lorem')
        ->assertDontSee('w-12 h-12');
});

it('cannot personalize wrong component', function () {
    $this->expectException(InvalidArgumentException::class);

    TasteUi::personalize()
        ->form('input2')
        ->block('base2', 'rounded-md p-4');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    TasteUi::personalize()
        ->alert()
        ->block('base2', 'rounded-md p-4');
});
