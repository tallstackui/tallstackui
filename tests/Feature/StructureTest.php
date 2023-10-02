<?php

use TasteUi\Contracts\Customizable;
use TasteUi\Support\Personalization;

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
            ->toImplement('TasteUi\Support\Personalizations\Contracts\Personalizable');
    })->with('personalizations');

    test('should returns component class path as string', function (string $index) {
        $component = (new Personalization($index))->instance()->component();

        $this->expect($component)
            ->toBeString();
    })->with('personalizations');

    test('throws exception if component name is wrong', function () {
        (new Personalization('LoremIpsum'))->instance();
    })->throws(InvalidArgumentException::class);
});
