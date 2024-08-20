<?php

use TallStackUi\Foundation\Components\Colors\ResolveColor;

describe('ColorProvider', function () {
    test('contain resolve method')
        ->expect(ResolveColor::class)
        ->toHaveMethod('of');
});
