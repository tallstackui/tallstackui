<?php

use TallStackUi\Foundation\ResolveConfiguration;

describe('ConfigurationProvider', function () {
    test('contains resolve method')
        ->expect(ResolveConfiguration::class)
        ->toHaveMethod('from')
        ->toHaveMethod('loading')
        ->toHaveMethod('modal')
        ->toHaveMethod('slide');
});
