<?php

use TallStackUi\Facades\TallStackUi;

test('can set prefix', function () {
    config()->set('tallstackui.prefix', 'ts-');

    expect(TallStackUi::component('alert'))->toBe('ts-alert');
});

test('can unset prefix', function () {
    config()->set('tallstackui.prefix', 'ts-');

    expect(TallStackUi::component()->remove('ts-alert'))->toBe('alert');
});

test('can get name without prefix', function () {
    expect(TallStackUi::component('alert'))->toBe('alert');
});
