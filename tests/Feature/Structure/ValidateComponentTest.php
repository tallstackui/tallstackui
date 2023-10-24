<?php

use TallStackUi\View\Personalizations\Support\ValidateComponent;

describe('ValidateComponent', function () {
    test('contains validate method')
        ->expect(ValidateComponent::class)
        ->toHaveMethod('validate');
});
