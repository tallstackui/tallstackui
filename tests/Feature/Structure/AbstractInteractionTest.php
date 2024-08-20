<?php

use TallStackUi\Actions\AbstractInteraction;
use TallStackUi\Actions\Banner;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;

test('class should be abstract')
    ->expect(AbstractInteraction::class)
    ->toBeAbstract();

test('implements abstraction action class')
    ->expect([Dialog::class, Toast::class, Banner::class])
    ->toExtend(AbstractInteraction::class);

test('abstract action class has method', function (string $method) {
    expect(AbstractInteraction::class)->toHaveMethod($method);
})->with([
    '__construct',
    'error',
    'info',
    'question',
    'success',
    'warning',
    'wireable',
    'event',
    'messages',
]);
