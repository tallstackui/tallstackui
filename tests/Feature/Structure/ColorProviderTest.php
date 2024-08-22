<?php

use TallStackUi\Foundation\Support\Colors\ResolveColor;

test('contains method')
    ->expect(ResolveColor::class)
    ->toHaveMethod('of');
