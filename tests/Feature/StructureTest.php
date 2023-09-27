<?php

test('should not use dangerous functions')
    ->expect(['dd', 'dump', 'exit', 'var_dump'])
    ->not
    ->toBeUsed();
