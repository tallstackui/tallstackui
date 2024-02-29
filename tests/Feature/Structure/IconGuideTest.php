<?php

use TallStackUi\Foundation\Support\Components\IconGuide;

describe('IconGuide', function () {
    test('class should not have constructor')
        ->expect(IconGuide::class)
        ->not
        ->toHaveConstructor();

    test('class has method', function (string $method) {
        expect(IconGuide::class)->toHaveMethod($method);
    })->with([
        'build',
        'internal',
        'configuration',
        'validate',
    ]);
});
