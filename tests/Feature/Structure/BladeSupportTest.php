<?php

use TallStackUi\Foundation\Support\Blade\Wireable;

test('class should have constructor')
    ->expect(Wireable::class)
    ->toHaveConstructor();

test('class has method', function (string $method) {
    expect(Wireable::class)->toHaveMethod($method);
})->with([
    'entangle',
    'json',
    'wire',
]);
