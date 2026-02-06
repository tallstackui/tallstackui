<?php

use TallStackUi\Customization\Contracts\Customization as CustomizationContract;
use TallStackUi\Customization\Customization;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

test('is customizable', function (string $index) {
    expect($index)->toImplement(CustomizationContract::class);
})->with('customization.components');

test('contains customization method', function (string $index) {
    expect($index)->toHaveMethod('customization');
})->with('customization.components');

test('contains constructor', function (string $index) {
    $ignores = [Dialog::class, Toast::class];

    if (in_array($index, $ignores)) {
        $this->markTestSkipped("[$index] doesn't have constructor"); // @phpstan-ignore-line
    }

    expect($index)->toHaveConstructor();
})->with('customization.components');

test('throws exception if component name is wrong', function () {
    $this->expectExceptionMessage('The method [foo-bar] is not supported');
    $this->expectException(RuntimeException::class);

    (new Customization('foo-bar'))->forward();
});

test('throws exception if not component was set', function () {
    $this->expectExceptionMessage('No component has been set');
    $this->expectException(RuntimeException::class);

    (new Customization)->forward();
});
