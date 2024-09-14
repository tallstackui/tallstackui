<?php

use TallStackUi\Foundation\Personalization\Personalization;

test('contains constructor', function () {
    expect(Personalization::class)->toHaveConstructor();
});

test('contains method', function (string $method) {
    expect(Personalization::class)->toHaveMethod($method);
})->with([
    'alert',
    'avatar',
    'badge',
    'banner',
    'block',
    'boolean',
    'button',
    'card',
    'clipboard',
    'dialog',
    'dropdown',
    'errors',
    'floating',
    'form',
    'instance',
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
