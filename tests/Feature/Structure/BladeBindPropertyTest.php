<?php

use TallStackUi\Foundation\Support\Blade\BladeBindProperty;

describe('BladeBindProperty', function () {
    test('class should have constructor')
        ->expect(BladeBindProperty::class)
        ->toHaveConstructor();

    test('class has method', function (string $method) {
        expect(BladeBindProperty::class)->toHaveMethod($method);
    })->with([
        'data',
        'bind',
        'error',
        'id',
    ]);
});
