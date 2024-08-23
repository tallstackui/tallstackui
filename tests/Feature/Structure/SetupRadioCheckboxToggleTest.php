<?php

use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Traits\Setup;

test('should be used only in checkbox, toggle and radio')
    ->expect(Setup::class)
    ->toOnlyBeUsedIn([Toggle::class, Radio::class, Checkbox::class]);

test('should have setup method', function () {
    expect(Setup::class)->toHaveMethod('setup');
});
