<?php

use TallStackUi\Foundation\Support\Blade\BladeSupport;

test('class should have constructor')
    ->expect(BladeSupport::class)
    ->toHaveConstructor();

test('class has method', function (string $method) {
    expect(BladeSupport::class)->toHaveMethod($method);
})->with([
    'entangle',
    'json',
    'wire',
]);
