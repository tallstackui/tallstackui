<?php

use TallStackUi\View\Personalizations\Support\Validation;

describe('Validation', function () {
    test('contains all methods', function (string $method) {
        expect(Validation::class)->toHaveMethod($method);
    })->with([
        'from',
        'dialog',
        'toast',
    ]);
});
