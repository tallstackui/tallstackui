<?php

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;

it('can be instantiated', function () {
    $personalization = TasteUi::personalize();

    expect($personalization)->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component and alias block', function () {
    $personalization = TasteUi::personalize('alert');

    expect($personalization->block(['base' => fn () => 'string']))->toBeInstanceOf(Personalizable::class);
});

it('can be instantiated with a component', function () {
    $personalization = TasteUi::personalize('alert');

    expect($personalization->instance())->toBeInstanceOf(Personalizable::class);
});

it('can be instantiated all components personalization', function () {

    foreach (Personalization::PERSONALIZABLES as $component => $personalization) {
        $personalization = TasteUi::personalize($component);

        expect($personalization->instance())->toBeInstanceOf(Personalizable::class);
    }

});

it('can be instantiated all components personalization extends of resource', function () {

    foreach (Personalization::PERSONALIZABLES as $personalization) {

        $this->assertInstanceOf(Resource::class, new $personalization);
    }

});


