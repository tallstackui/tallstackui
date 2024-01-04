<?php

use TallStackUi\Foundation\Personalization\BuildScopePersonalization;

describe('BuildScopePersonalization', function () {
    test('BuildScopePersonalization should have construct')
        ->expect(BuildScopePersonalization::class)
        ->toHaveConstructor();

    test('BuildScopePersonalization should have all the expected methods', function (string $method) {
        expect(BuildScopePersonalization::class)
            ->toHaveMethod($method);
    })->with([
        '__invoke',
        'append',
        'common',
        'prepend',
        'replace',
        'remove',
    ]);
});
