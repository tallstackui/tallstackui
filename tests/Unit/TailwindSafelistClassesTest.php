<?php

use TallStackUi\View\Personalizations\Support\TailwindSafelistClasses;

const KEYS = [
    'primary',
    'secondary',
    'dark',
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
    $constants = collect((new ReflectionClass(TailwindSafelistClasses::class))->getConstants())
        ->filter(fn ($value, $key) => $key === $name)
        ->toArray();

    expect(array_keys($constants[$name]))->toBe(KEYS);
})->with([
    'TEXT',
    'BG',
    'HOVER_RING',
    'PEER_CHECKED_BG',
    'PEER_FOCUS_RING',
    'RING_OFFSET',
    'BORDER',
    'RING',
    'HOVER_RING',
]);
