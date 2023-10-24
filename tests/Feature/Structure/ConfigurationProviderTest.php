<?php

use TallStackUi\View\Personalizations\Providers\ConfigurationProvider;

describe('ConfigurationProvider', function () {
    test('contains all methods', function (string $method) {
        expect(ConfigurationProvider::class)->toHaveMethod($method);
    })->with([
        'resolve',
        'dialog',
        'toast',
    ]);
});
