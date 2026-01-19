<?php

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Support\Concerns\BuildRawIcon;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Tooltip;

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
