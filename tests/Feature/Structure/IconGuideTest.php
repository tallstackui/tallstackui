<?php

use TallStackUi\Foundation\Support\Icons\IconGuide;
use TallStackUi\Foundation\Support\Icons\IconGuideMap;

test('class should not have constructor')
    ->expect(IconGuideMap::class)
    ->not
    ->toHaveConstructor();

test('class has method', function (string $method) {
    expect(IconGuideMap::class)->toHaveMethod($method);
})->with([
    'build',
    'internal',
    'configuration',
    'validate',
]);

test('helper class has method', function (string $method) {
    expect(IconGuide::class)->toHaveMethod($method);
})->with([
    'resolve',
    'get',
    'styles',
    'google',
    'hero',
    'phosphor',
    'tabler',
    'lucide',
]);
