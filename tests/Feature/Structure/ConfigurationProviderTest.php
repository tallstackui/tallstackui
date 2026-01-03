<?php

use TallStackUi\Support\Configurations\CompileConfigurations;

test('class has method', function (string $method) {
    expect(CompileConfigurations::class)->toHaveMethod($method);
})->with([
    'of',
    'loading',
    'modal',
    'slide',
]);
