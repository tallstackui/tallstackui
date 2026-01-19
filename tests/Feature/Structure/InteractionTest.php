<?php

use TallStackUi\Traits\Interactions;

test('contains methods', function (string $method) {
    expect(Interactions::class)->toHaveMethod($method);
})->with([
    'banner',
    'toast',
    'dialog',
]);
