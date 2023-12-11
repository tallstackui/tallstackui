<?php

use TallStackUi\Foundation\ValidateComponent;

describe('ValidateComponent', function () {
    test('contains validate method')
        ->expect(ValidateComponent::class)
        ->toHaveMethod('validate');
});
