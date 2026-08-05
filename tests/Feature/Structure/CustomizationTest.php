<?php

use TallStackUi\Customization\Customization;

test('contains constructor', function () {
    expect(Customization::class)->toHaveConstructor();
});

// Every soft customization key is resolved by Customization::forward(), which
// looks up a method named after the key's first segment. A component shipped
// without that method throws on any customize() call, so the coverage is
// derived from the attribute instead of a hand-kept list.
test('exposes a method for every soft customization component', function () {
    $missing = [];

    foreach (array_keys(__ts_soft_customization_components()) as $prefixed) {
        $key = str_replace('ts-ui::customization.', '', $prefixed);
        $parts = explode('.', $key);
        $main = $parts[0];

        if (! method_exists(Customization::class, $main)) {
            $missing[] = $key;

            continue;
        }

        if (count($parts) === 1) {
            continue;
        }

        if ((new ReflectionMethod(Customization::class, $main))->getParameters()[0]->getName() !== 'component') {
            $missing[] = $key;
        }
    }

    expect($missing)->toBe([]);
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
    'gallery',
    'link',
    'loading',
    'modal',
    'progress',
    'qrCode',
    'rating',
    'reaction',
    'select',
    'slide',
    'spinner',
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
