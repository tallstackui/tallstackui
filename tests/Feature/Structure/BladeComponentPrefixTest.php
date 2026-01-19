<?php

use TallStackUi\Foundation\Support\Blade\ComponentPrefix;

test('class should have constructor')
    ->expect(ComponentPrefix::class)->toHaveConstructor();

test('class has methods', function () {
    expect(ComponentPrefix::class)
        ->toHaveMethod('add')
        ->toHaveMethod('remove');
});
