<?php

use TallStackUi\Foundation\ResolveConfiguration;

describe('ResolveConfiguration', function () {
    test('class has method', function (string $method) {
        expect(ResolveConfiguration::class)->toHaveMethod($method);
    })->with([
        'from',
        'loading',
        'modal',
        'slide',
    ]);
});
