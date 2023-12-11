<?php

use TallStackUi\Support\ValidateComponent;

describe('ValidateComponent', function () {
    test('contains validate method')
        ->expect(ValidateComponent::class)
        ->toHaveMethod('validate');
});
