<?php

use TallStackUi\Foundation\Support\Blade\BindProperty;

test('class should have constructor')
    ->expect(BindProperty::class)
    ->toHaveConstructor();

test('class has method', function (string $method) {
    expect(BindProperty::class)->toHaveMethod($method);
})->with([
    'toArray',
    'toCollection',
    'bind',
    'error',
    'id',
]);
