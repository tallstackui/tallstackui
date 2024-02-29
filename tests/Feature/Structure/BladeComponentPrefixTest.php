<?php

use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;

describe('BladeComponentPrefix', function () {
    test('class should have constructor')
        ->expect(BladeComponentPrefix::class)
        ->toHaveConstructor();

    test('class has invoke method', function () {
        expect(BladeComponentPrefix::class)->toBeInvokable();
    });
});
