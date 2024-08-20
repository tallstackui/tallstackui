<?php

use TallStackUi\Foundation\Components\Configurations\ResolveConfiguration;

test('class has method', function (string $method) {
    expect(ResolveConfiguration::class)->toHaveMethod($method);
})->with([
    'of',
    'loading',
    'modal',
    'slide',
]);
