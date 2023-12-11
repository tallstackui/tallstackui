<?php

use TallStackUi\Foundation\Colors\ResolveColor;

describe('ColorProvider', function () {
    test('contain resolve method')
        ->expect(ResolveColor::class)
        ->toHaveMethod('from');
});
