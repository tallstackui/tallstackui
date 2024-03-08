<?php

use TallStackUi\View\Components\Clipboard;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Number;
use TallStackUi\View\Components\Form\Tag;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Select\Native;
use TallStackUi\View\Components\Select\Styled;

describe('DefaultInputClasses', function () {
    test('should be used only specific components')
        ->expect(DefaultInputClasses::class)
        ->toOnlyBeUsedIn([
            Input::class,
            Number::class,
            Tag::class,
            Textarea::class,
            Native::class,
            Styled::class,
            Clipboard::class,
        ]);

    test('should have methods', function (string $method) {
        expect(DefaultInputClasses::class)->toHaveMethod($method);
    })->with([
        'error',
        'input',
    ]);
});
