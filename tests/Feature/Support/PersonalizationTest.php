<?php

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\Personalization;
use TallStackUi\View\Personalizations\PersonalizationResource;

it('can be instantiated', function () {
    expect(TallStackUi::personalize())->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component', function () {
    expect(TallStackUi::personalize('alert')
        ->block(['wrapper' => fn () => 'string']))
        ->toBeInstanceOf(Personalizable::class);
});

it('can instantiate all components', function (string $component) {
    expect(TallStackUi::personalize($component)->instance())->toBeInstanceOf(Personalizable::class);
})->with('personalizations.keys');

it('can instanciate all components extends of resource', function (string $component) {
    expect(new $component)->toBeInstanceOf(PersonalizationResource::class);
})->with('personalizations.classes');

it('can personalize using facade and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TallStackUi::personalize('alert')
        ->block('wrapper', 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize using method and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize using method and closure', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', fn () => 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize using method and array', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    TallStackUi::personalize()
        ->alert()
        ->block([
            'wrapper' => 'p-4',
        ]);

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize in sequenece', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('w-12 h-12');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4')
        ->and()
        ->avatar()
        ->block('wrapper', 'inline-flex shrink-0 items-center justify-center overflow-hidden text-xl w-20 h-20');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('Lorem')
        ->assertDontSee('w-12 h-12');
});

it('cannot personalize wrong component', function () {
    $this->expectException(InvalidArgumentException::class);

    TallStackUi::personalize()
        ->form('input2')
        ->block('base2', 'rounded-md p-4');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    TallStackUi::personalize()
        ->alert()
        ->block('base2', 'rounded-md p-4');
});
