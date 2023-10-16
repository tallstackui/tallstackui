<?php

use TallStackUi\View\Personalizations\Support\ValidateConfiguration;

describe('ValidateConfiguration', function () {
    test('contains all methods', function (string $method) {
        expect(ValidateConfiguration::class)->toHaveMethod($method);
    })->with([
        'from',
        'dialog',
        'toast',
    ]);
});
