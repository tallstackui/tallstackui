<?php

use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Traits\SetupRadioCheckboxToggle;

describe('SetupRadioCheckboxToggle', function () {
    test('should be used only in checkbox, toggle and radio')
        ->expect(SetupRadioCheckboxToggle::class)
        ->toOnlyBeUsedIn([Toggle::class, Radio::class, Checkbox::class]);

    test('should have methods', function (string $method) {
        expect(SetupRadioCheckboxToggle::class)->toHaveMethod($method);
    })->with([
        'sloteable',
        'setup',
    ]);
});
