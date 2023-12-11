<?php

use TallStackUi\Foundation\ResolveConfiguration;

describe('ConfigurationProvider', function () {
    test('contains resolve method')
        ->expect(ResolveConfiguration::class)
        ->toHaveMethod('resolve')
        ->toHaveMethod('modal');
});
