<?php

use TallStackUi\Foundation\Support\Components\UploadComponentFileAdapter;

test('class should have constructor')
    ->expect(UploadComponentFileAdapter::class)
    ->toHaveConstructor();

test('class should be invokable')
    ->expect(UploadComponentFileAdapter::class)
    ->toBeInvokable();

test('class has method', function (string $method) {
    expect(UploadComponentFileAdapter::class)->toHaveMethod($method);
})->with([
    'image',
    'size',
    'static',
    'upload',
]);
