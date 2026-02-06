<?php

use TallStackUi\Components\Form\Checkbox\Component as Checkbox;
use TallStackUi\Components\Form\Radio\Component as Radio;
use TallStackUi\Components\Form\Toggle\Component as Toggle;
use TallStackUi\Components\Traits\FormSetup;

test('should be used only in checkbox, toggle and radio')
    ->expect(FormSetup::class)
    ->toOnlyBeUsedIn([Toggle::class, Radio::class, Checkbox::class]);

test('should have setup method', function () {
    expect(FormSetup::class)->toHaveMethod('setup');
});
