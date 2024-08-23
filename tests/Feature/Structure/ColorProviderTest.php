<?php

use TallStackUi\Foundation\Support\Colors\CompileColors;

test('contains method')
    ->expect(CompileColors::class)
    ->toHaveMethod('of');
