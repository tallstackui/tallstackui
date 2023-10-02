<?php

use TasteUi\Support\Personalization;

test('should not use dangerous functions')
    ->expect(['dd', 'dump', 'exit', 'var_dump'])
    ->not
    ->toBeUsed();

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
