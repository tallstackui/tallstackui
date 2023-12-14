<?php

use TallStackUi\Foundation\Personalization\Contracts\Personalization as PersonalizationContract;
use TallStackUi\Foundation\Personalization\Personalization;

describe('TallStackUi Components', function () {
    test('is customizable', function (string $index) {
        expect($index)->toImplement(PersonalizationContract::class);
    })->with('personalizations.components');

    test('contains personalization method', function (string $index) {
        expect($index)->toHaveMethod('personalization');
    })->with('personalizations.components');

    test('contains constructor', function (string $index) {
        $ignores = [
            'TallStackUi\View\Components\Interaction\Dialog',
            'TallStackUi\View\Components\Interaction\Toast',
        ];

        if (in_array($index, $ignores)) {
            test()->markTestSkipped("[$index] doesn't have constructor"); // @phpstan-ignore-line
        }

        expect($index)
            ->toHaveConstructor();
    })->with('personalizations.components');
});

describe('Components Personalization', function () {
    test('throws exception if component name is wrong', function () {
        (new Personalization('foo-bar'))->instance();
    })->throws(InvalidArgumentException::class);
});
