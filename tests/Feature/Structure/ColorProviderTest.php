<?php

use TallStackUi\Foundation\Components\Colors\ResolveColor;

test('contains method')
    ->expect(ResolveColor::class)
    ->toHaveMethod('of');
