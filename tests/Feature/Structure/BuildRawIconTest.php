<?php

use TallStackUi\Foundation\Traits\BuildRawIcon;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Tooltip;

describe('BuildRawIcon', function () {
    test('trait has method', function () {
        expect(BuildRawIcon::class)->toHaveMethod('icon');
    });

    test('trait should only be used in', function () {
        expect(BuildRawIcon::class)
            ->toOnlyBeUsedIn([Icon::class, Tooltip::class]);
    });
});
