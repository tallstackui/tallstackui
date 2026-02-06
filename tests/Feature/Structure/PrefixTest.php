<?php

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Blade\ComponentPrefix;

test('can set prefix', function () {
    config()->set('ts-ui.prefix', 'ts-');

    expect(TallStackUi::prefix('alert'))->toBe('ts-alert');
});

test('can unset prefix', function () {
    config()->set('ts-ui.prefix', 'ts-');

    expect(TallStackUi::prefix()->remove('ts-alert'))->toBe('alert');
});

test('can get name without prefix', function () {
    expect(TallStackUi::prefix('alert'))->toBe('alert');
});

test('can get ComponentPrefix instance', function () {
    config()->set('ts-ui.prefix', 'ts-');

    expect(TallStackUi::prefix())->toBeInstanceOf(ComponentPrefix::class);
});
