<?php

use TallStackUi\Actions\AbstractInteraction;
use TallStackUi\Actions\Banner;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;
use TallStackUi\Foundation\Traits\MergeConditionalAttributes;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Password;

describe('MergeConditionalAttributes', function () {
    test('used only in needed classes')
        ->expect(MergeConditionalAttributes::class)
        ->toOnlyBeUsedIn([Color::class, Password::class]);

    test('trait has method', function () {
        expect(MergeConditionalAttributes::class)->toHaveMethod('merge');
    });
});
