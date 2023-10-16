<?php

use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Personalization;

describe('TallStackUi Components', function () {
    test('is customizable', function (string $index) {
        expect($index)->toImplement(Personalize::class);
    })->with('components');

    test('contains personalization method', function (string $index) {
        expect($index)->toHaveMethod('personalization');
    })->with('components');

    test('contains constructor', function (string $index) {
        expect($index)->toHaveConstructor();
    })->with('components');
});

describe('Components Personalization', function () {
    test('should implements Personalizable contract', function (string $index) {
        $component = Personalization::PERSONALIZABLES[$index];

        expect($component)->toImplement(Personalizable::class);
    })->with('personalizations.keys');

    test('throws exception if component name is wrong', function () {
        (new Personalization('foo-bar'))->instance();
    })->throws(InvalidArgumentException::class);
});
