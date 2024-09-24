<?php

use TallStackUi\Foundation\Personalization\Contracts\Personalization as PersonalizationContract;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

test('is customizable', function (string $index) {
    expect($index)->toImplement(PersonalizationContract::class);
})->with('personalizations.components');

test('contains personalization method', function (string $index) {
    expect($index)->toHaveMethod('personalization');
})->with('personalizations.components');

test('contains constructor', function (string $index) {
    $ignores = [Dialog::class, Toast::class];

    if (in_array($index, $ignores)) {
        $this->markTestSkipped("[$index] doesn't have constructor"); // @phpstan-ignore-line
    }

    expect($index)->toHaveConstructor();
})->with('personalizations.components');

test('throws exception if component name is wrong', function () {
    $this->expectExceptionMessage('The method [foo-bar] is not supported');
    $this->expectException(RuntimeException::class);

    (new Personalization('foo-bar'))->instance();
});

test('throws exception if not component was set', function () {
    $this->expectExceptionMessage('No component has been set');
    $this->expectException(RuntimeException::class);

    (new Personalization)->instance();
});
