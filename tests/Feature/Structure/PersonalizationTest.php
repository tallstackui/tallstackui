<?php

use TallStackUi\Support\Personalizations\Personalization;

test('Personalization', function (string $method) {
    expect(Personalization::class)->toHaveMethod($method);
})->with([
    '__construct',
    'block',
    'instance',
    'alert',
    'modal',
    'button',
    'avatar',
    'badge',
    'card',
    'dialog',
    'dropdown',
    'errors',
    'toast',
    'form',
    'select',
    'tab',
    'tooltip',
    'wrapper',
    'component',
]);
