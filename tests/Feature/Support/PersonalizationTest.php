<?php

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Components\PersonalizationResource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;

it('can be instantiated', function () {
    expect(TasteUi::personalize())->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component and alias block', function () {
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

it('can personalize using reference of method')->todo();

it('can personalize using component name')->todo();

it('can personalize block using string')->todo();

it('can personalize block using array')->todo();

it('can personalize in sequenece')->todo();
