<?php

use TallStackUi\Actions\AbstractInteraction;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;
use TallStackUi\Facades\TallStackUi as Facade;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;
use TallStackUi\Support\Color;
use TallStackUi\Traits\Interactions;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Personalization;

test('should not use dangerous functions')
    ->expect(['dd', 'dump', 'exit', 'var_dump'])
    ->not
    ->toBeUsed();

describe('tallstackui components', function () {
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

describe('components from personalization', function () {
    test('should implements Personalizable contract', function (string $index) {
        $component = Personalization::PERSONALIZABLES[$index];

        expect($component)->toImplement(Personalizable::class);
    })->with('personalizations.keys');

    test('throws exception if component name is wrong', function () {
        (new Personalization('foo-bar'))->instance();
    })->throws(InvalidArgumentException::class);
});

describe('AbstractInteraction class tests', function () {
    test('class should be abstract')
        ->expect(AbstractInteraction::class)
        ->toBeAbstract();

    test('implements abstraction action class')
        ->expect([Dialog::class, Toast::class])
        ->toExtend(AbstractInteraction::class);

    test('abstract action class has method', function (string $method) {
        expect(AbstractInteraction::class)->toHaveMethod($method);
    })->with([
        'success',
        'error',
        'info',
        'warning',
        'confirm',
        'send',
        'base',
    ]);
});

describe('Interactions trait tests', function () {
    test('contains methods', function (string $method) {
        expect(Interactions::class)->toHaveMethod($method);
    })->with([
        'toast',
        'dialog',
    ]);
});

describe('Color tests', function () {
    test('should be final')
        ->expect(Color::class)
        ->toBeFinal();

    test('implements stringable')
        ->expect(Color::class)
        ->toImplement(Stringable::class);

    test('should have methods', function (string $method) {
        expect(Color::class)->toHaveMethod($method);
    })->with([
        'set',
        'merge',
        'mergeWhen',
        'mergeUnless',
        'prepend',
        'append',
        'get',
    ]);

    test('manage class', function () {
        $color = Facade::colors()
            ->set('bg', 'red', '100')
            ->merge('bg', 'blue', '200')
            ->mergeWhen(true, 'bg', 'green', '300')
            ->mergeUnless(false, 'bg', 'yellow', '400')
            ->prepend('bg-purple-500')
            ->append('bg-pink-600')
            ->get();

        expect($color)->toBe('bg-purple-500 bg-red-100 bg-blue-200 bg-green-300 bg-yellow-400 bg-pink-600');
    });
});

test('personalization class should have all personalization methods', function (string $method) {
    expect(Personalization::class)->toHaveMethod($method);
})->with([
    '__construct',
    'block',
    'instance',
    'alert',
    'modal',
    'button',
    'avatar',
    'badge',
    'card',
    'dialog',
    'dropdown',
    'error',
    'errors',
    'toast',
    'form',
    'hint',
    'select',
    'tab',
    'tooltip',
    'wrapper',
    'component',
]);

describe('TallStackUiAssetsController tests', function () {
    test('contains all methods', function () {
        expect(TallStackUiAssetsController::class)
            ->toHaveMethod('scripts')
            ->toHaveMethod('styles');
    });
});
