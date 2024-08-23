<?php

use TallStackUi\Foundation\Support\Configurations\CompileConfigurations;

test('class has method', function (string $method) {
    expect(CompileConfigurations::class)->toHaveMethod($method);
})->with([
    'of',
    'loading',
    'modal',
    'slide',
]);
