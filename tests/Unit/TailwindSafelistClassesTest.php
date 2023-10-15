<?php

use TallStackUi\View\Personalizations\Support\TailwindSafelistClasses;

const KEYS = [
    'primary',
    'secondary',
    'white',
    'black',
    'slate',
    'gray',
    'zinc',
    'neutral',
    'stone',
    'red',
    'orange',
    'amber',
    'yellow',
    'lime',
    'green',
    'emerald',
    'teal',
    'cyan',
    'sky',
    'blue',
    'indigo',
    'violet',
    'purple',
    'fuchsia',
    'pink',
    'rose',
];

test('should have constant and keys', function ($name) {
    $constants = (new ReflectionClass(TailwindSafelistClasses::class))->getConstants();

    expect(array_keys($constants[$name]))->toBe(KEYS);
})->with([
    'TEXT',
    'BG',
    'HOVER_BG',
    'BORDER',
    'RING',
    'HOVER_RING',
]);
