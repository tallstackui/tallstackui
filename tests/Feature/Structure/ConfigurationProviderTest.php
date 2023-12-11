<?php

use TallStackUi\Foundation\Providers\ConfigurationProvider;

describe('ConfigurationProvider', function () {
    test('contains resolve method')
        ->expect(ConfigurationProvider::class)
        ->toHaveMethod('resolve')
        ->toHaveMethod('modal');
});
