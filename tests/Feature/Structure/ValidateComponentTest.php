<?php

use TallStackUi\View\Personalizations\Support\ValidateComponent;

describe('ValidateComponent', function () {
    test('contains all methods', function () {
        expect(ValidateComponent::class)->toHaveMethod('validate');
    });
});
