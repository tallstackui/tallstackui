<?php

use TallStackUi\Facades\TallStackUi as Facade;
use TallStackUi\View\Personalizations\Support\Color;

describe('Color tests', function () {
    test('should be final')
        ->expect(Color::class)
        ->toBeFinal();

    test('implements stringable')
        ->expect(Color::class)
        ->toImplement(Stringable::class);

    test('should have methods', function (string $method) {
        expect(Color::class)->toHaveMethod($method);
    })->with([
        'set',
        'merge',
        'mergeWhen',
        'mergeUnless',
        'prepend',
        'append',
        'get',
    ]);

    test('manage class', function () {
        $color = Facade::colors()
            ->set('bg', 'red', '100')
            ->merge('bg', 'blue', '200')
            ->mergeWhen(true, 'bg', 'green', '300')
            ->mergeUnless(false, 'bg', 'yellow', '400')
            ->prepend('bg-purple-500')
            ->append('bg-pink-600')
            ->get();

        expect($color)->toBe('bg-purple-500 bg-red-100 bg-blue-200 bg-green-300 bg-yellow-400 bg-pink-600');
    });
});
