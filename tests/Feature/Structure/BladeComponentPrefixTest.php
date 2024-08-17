<?php

use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;

describe('BladeComponentPrefix', function () {
    test('class should have constructor')
        ->expect(BladeComponentPrefix::class)
        ->toHaveConstructor();

    test('class has methods', function () {
        expect(BladeComponentPrefix::class)
            ->toHaveMethod('add')
            ->toHaveMethod('remove');
    });
});
