<?php

use TallStackUi\Customization\Customization;

test('contains constructor', function () {
    expect(Customization::class)->toHaveConstructor();
});

test('contains method', function (string $method) {
    expect(Customization::class)->toHaveMethod($method);
})->with([
    'alert',
    'avatar',
    'badge',
    'banner',
    'block',
    'boolean',
    'button',
    'card',
    'chart',
    'clipboard',
    'dialog',
    'dropdown',
    'errors',
    'floating',
    'form',
    'forward',
    'link',
    'loading',
    'modal',
    'progress',
    'rating',
    'reaction',
    'select',
    'slide',
    'stats',
    'step',
    'tab',
    'table',
    'themeSwitch',
    'toast',
    'tooltip',
    'wrapper',
    'component',
]);
