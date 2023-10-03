<?php

use TasteUi\Actions\AbstractInteraction;
use TasteUi\Actions\Dialog;
use TasteUi\Actions\Toast;
use TasteUi\Contracts\Customizable;
use TasteUi\Support\Elements\Color;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Contracts\Personalizable;

test('should not use dangerous functions')
    ->expect(['dd', 'dump', 'exit', 'var_dump'])
    ->not
    ->toBeUsed();

describe('tasteui components', function () {
    test('is customizable', function (string $index) {
        $this->expect($index)->toImplement(Customizable::class);
    })->with('components');

    test('contains tasteUiClasses method', function (string $index) {
        $this->expect($index)->toHaveMethod('tasteUiClasses');
    })->with('components');

    test('contains customization method', function (string $index) {
        $this->expect($index)->toHaveMethod('customization');
    })->with('components');

    test('contains constructor', function (string $index) {
        $this->expect($index)->toHaveConstructor();
    })->with('components');
});

describe('components from personalization', function () {
    test('should implements Personalizable contract', function (string $index) {
        $component = Personalization::PERSONALIZABLES[$index];

        $this->expect($component)
            ->toImplement(Personalizable::class);
    })->with('personalizations');

    test('throws exception if component name is wrong', function () {
        (new Personalization('foo-bar'))->instance();
    })->throws(InvalidArgumentException::class);
});

describe('abstract interaction', function () {
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

describe('color class', function () {
    test('should be final')
        ->expect(Color::class)
        ->toBeFinal();

    test('implements stringable')
        ->expect(Color::class)
        ->toImplement(Stringable::class);

    test('should have methods', function (string $method) {
        expect(Color::class)
            ->toHaveMethod($method);
    })->with([
        'set',
        'merge',
        'mergeWhen',
        'mergeUnless',
        'prepend',
        'append',
        'get',
        'validate',
    ]);
});
