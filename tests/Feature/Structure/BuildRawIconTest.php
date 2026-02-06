<?php

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Components\Icon\Component as Icon;
use TallStackUi\Components\Tooltip\Component as Tooltip;
use TallStackUi\Support\Concerns\BuildRawIcon;

test('trait has method', function () {
    expect(BuildRawIcon::class)->toHaveMethod('raw');
});

test('trait should only be used in', function () {
    expect(BuildRawIcon::class)->toOnlyBeUsedIn([Icon::class, Tooltip::class]);
});

test('should return the correct icon', function () {
    $icon = new Icon(name: 'envelope');

    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('heroicons.solid.envelope');
});

test('should replace icon style', function () {
    $icon = new Icon(name: 'envelope');

    $icon->attributes = new ComponentAttributeBag([
        'outline' => true,
    ]);

    expect($icon->raw())->toBe('heroicons.outline.envelope');
});
