<?php

use TallStackUi\Foundation\Support\Concerns\BuildRawIcon;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Tooltip;

test('trait has method', function () {
    expect(BuildRawIcon::class)->toHaveMethod('icon');
});

test('trait should only be used in', function () {
    expect(BuildRawIcon::class)->toOnlyBeUsedIn([Icon::class, Tooltip::class]);
});
