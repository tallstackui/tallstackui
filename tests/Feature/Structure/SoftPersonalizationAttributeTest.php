<?php

use TallStackUi\Attributes\SoftCustomization;

test('can implement the attribute', function (string $index) {
    expect($index)->toHaveAttribute(SoftCustomization::class);
})->with('personalizations.components');
