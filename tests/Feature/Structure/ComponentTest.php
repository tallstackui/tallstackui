<?php

use TallStackUi\Support\Personalizations\Contracts\Personalization as PersonalizationContract;
use TallStackUi\Support\Personalizations\Personalization;

describe('TallStackUi Components', function () {
    test('is customizable', function (string $index) {
        expect($index)->toImplement(PersonalizationContract::class);
    })->with('personalizations.components');

    test('contains personalization method', function (string $index) {
        expect($index)->toHaveMethod('personalization');
    })->with('personalizations.components');

    test('contains constructor', function (string $index) {
        expect($index)->toHaveConstructor();
    })->with('personalizations.components');
});

describe('Components Personalization', function () {
    test('throws exception if component name is wrong', function () {
        (new Personalization('foo-bar'))->instance();
    })->throws(InvalidArgumentException::class);
});
