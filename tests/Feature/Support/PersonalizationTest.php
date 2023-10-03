<?php

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;

it('can be instantiated', function () {
    $personalization = TasteUi::personalize();

    expect($personalization)->toBeInstanceOf(Personalization::class);
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

it('Checks if all defined docBlocks correspond to a customizable block', function () {

    foreach (Personalization::PERSONALIZABLES as $component => $customization) {

        $personalization = TasteUi::personalize($component)->instance();

        $reflection = new ReflectionClass($personalization);

        preg_match_all('/@method\s+\$this\s+(\w+)\(/', $reflection->getDocComment(), $matches);

        foreach ($matches[1] as $method) {
            expect($personalization->$method('string'))->toBeInstanceOf(Personalizable::class);
        }

    }

});
