<?php

use TallStackUi\Foundation\Traits\MergeAttributes;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Password;

describe('MergeConditionalAttributes', function () {
    test('used only in needed classes')
        ->expect(MergeAttributes::class)
        ->toOnlyBeUsedIn([Color::class, Password::class]);

    test('trait has method', function () {
        expect(MergeAttributes::class)->toHaveMethod('merge');
    });
});
