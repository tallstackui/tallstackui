<?php

use TallStackUi\Foundation\Support\Concerns\MergeAttributes;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Password;

test('used only in needed classes')
    ->expect(MergeAttributes::class)
    ->toOnlyBeUsedIn([Color::class, Password::class, Circle::class]);

test('trait has method', function (string $method) {
    expect(MergeAttributes::class)->toHaveMethod($method);
})->with([
    'merge',
    'mergeWhen',
    'mergeUnless',
]);
