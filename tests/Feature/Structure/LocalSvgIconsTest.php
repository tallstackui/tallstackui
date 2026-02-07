<?php

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Components\Icon\Component as Icon;
use TallStackUi\Support\Icons\IconGuideMap;

beforeEach(function () {
    __ts_get_component_configuration('', flush: true);
});

function local(string $type = 'views/components/svg', array $guide = []): void
{
    config()->set('ts-ui.components.icon', [
        Icon::class,
        [
            'type' => $type,
            'style' => 'solid',
            'custom' => [
                'guide' => $guide,
            ],
        ],
    ]);

    __ts_get_component_configuration('', flush: true);
}

test('should resolve local SVG icon', function () {
    local();

    $icon = new Icon(name: 'check');
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('svg.check');
});

test('should extract namespace from nested path', function () {
    local('views/components/icons/custom');

    $icon = new Icon(name: 'alert');
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('icons.custom.alert');
});

test('should use guide mapping for internal icons', function () {
    local(guide: [
        'check-circle' => 'success',
        'x-circle' => 'error',
    ]);

    $icon = new Icon(icon: 'check-circle', internal: true);
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('svg.success');
});

test('should use key as filename when guide mapping is null', function () {
    local(guide: [
        'check-circle' => null,
    ]);

    $icon = new Icon(icon: 'check-circle', internal: true);
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('svg.check-circle');
});

test('should ignore path parameter', function () {
    local();

    $icon = new Icon(name: 'check');
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw('ts-ui::icon.'))->toBe('svg.check');
});

test('should preserve dots as directory separators', function () {
    local();

    $icon = new Icon(name: 'social.github');
    $icon->attributes = new ComponentAttributeBag([]);

    expect($icon->raw())->toBe('svg.social.github');
});

test('should ignore style attributes', function () {
    local();

    $icon = new Icon(name: 'check');
    $icon->attributes = new ComponentAttributeBag(['outline' => true]);

    expect($icon->raw())->toBe('svg.check');
});

test('internal method should return key as-is', function () {
    local();

    expect(IconGuideMap::internal('check-circle'))->toBe('check-circle');
});
