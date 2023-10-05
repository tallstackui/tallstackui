<?php

use TasteUi\Facades\TasteUi;

test('can mock `directives->scripts`', function () {
    TasteUi::shouldReceive('directives->scripts')
        ->andReturn('foo-bar');

    $script = TasteUi::directives()->scripts();

    expect($script)->toBe('foo-bar');
});

test('can mock `directives->styles`', function () {
    TasteUi::shouldReceive('directives->styles')
        ->andReturn('foo-bar');

    $styles = TasteUi::directives()->styles();

    expect($styles)->toBe('foo-bar');
});

test('can render script', function () {
    $script = TasteUi::directives()->scripts();

    expect(str_contains($script, 'http://localhost/tasteui/scripts'))->toBeTrue();
});

test('can render styles', function () {
    $styles = TasteUi::directives()->styles();

    expect(str_contains($styles, 'http://localhost/tasteui/styles'))->toBeTrue();
});
