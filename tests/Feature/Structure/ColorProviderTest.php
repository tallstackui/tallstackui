<?php

use TallStackUi\View\Personalizations\Providers\ColorProvider;

describe('ColorProvider', function () {
    test('contains all methods', function (string $method) {
        expect(ColorProvider::class)->toHaveMethod($method);
    })->with([
        'resolve',
        'alert',
        'avatar',
        'badge',
        'button',
        'errors',
        'radio',
        'toggle',
        'tooltip',
    ]);

    test('contains constructor', function () {
        expect(ColorProvider::class)->toHaveConstructor();
    });
});
