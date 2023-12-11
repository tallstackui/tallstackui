<?php

use TallStackUi\Providers\ColorProvider;

describe('ColorProvider', function () {
    test('contain resolve method')
        ->expect(ColorProvider::class)
        ->toHaveMethod('resolve');
});
