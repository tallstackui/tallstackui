<?php

use TallStackUi\Traits\Interactions;

describe('Interactions', function () {
    test('contains methods', function (string $method) {
        expect(Interactions::class)->toHaveMethod($method);
    })->with([
        'toast',
        'dialog',
    ]);
});
