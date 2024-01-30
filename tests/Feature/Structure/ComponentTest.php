<?php

use TallStackUi\Foundation\Personalization\Contracts\Personalization as PersonalizationContract;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

describe('TallStackUi Components', function () {
    test('is customizable', function (string $index) {
        expect($index)->toImplement(PersonalizationContract::class);
    })->with('personalizations.components');

    test('contains personalization method', function (string $index) {
        expect($index)->toHaveMethod('personalization');
    })->with('personalizations.components');

    test('contains constructor', function (string $index) {
        $ignores = [
            Dialog::class,
            Toast::class,
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
    })->throws(RuntimeException::class);
});
