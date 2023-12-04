<?php

use TallStackUi\View\Personalizations\SoftPersonalization;

test('can implement the attribute', function (string $index) {
    expect($index)->toHaveAttribute(SoftPersonalization::class);
})->with('personalizations.components');
