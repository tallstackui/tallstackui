<?php

use TallStackUi\Facades\TallStackUi;

test('can mock directives->scripts', function () {
    TallStackUi::shouldReceive('directives->scripts')
        ->andReturn('foo-bar');

    $script = TallStackUi::directives()->script();

    expect($script)->toBe('foo-bar');
});

test('can mock directives->styles', function () {
    TallStackUi::shouldReceive('directives->styles')
        ->andReturn('foo-bar');

    $styles = TallStackUi::directives()->style();

    expect($styles)->toBe('foo-bar');
});

test('can render script', function () {
    $script = TallStackUi::directives()->script();

    expect(str_contains($script, 'http://localhost/tallstackui/scripts'))->toBeTrue();
});

test('can render styles', function () {
    $styles = TallStackUi::directives()->style();

    expect(str_contains($styles, 'http://localhost/tallstackui/styles'))->toBeTrue();
});
