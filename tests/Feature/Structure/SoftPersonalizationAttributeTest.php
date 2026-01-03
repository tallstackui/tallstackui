<?php

use TallStackUi\Attributes\SoftPersonalization;

test('can implement the attribute', function (string $index) {
    expect($index)->toHaveAttribute(SoftPersonalization::class);
})->with('personalizations.components');
