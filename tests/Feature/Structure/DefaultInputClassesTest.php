<?php

use TallStackUi\Components\Clipboard\Component as Clipboard;
use TallStackUi\Components\Form\Input\Component as Input;
use TallStackUi\Components\Form\InputSelect\Component as InputSelect;
use TallStackUi\Components\Form\Number\Component as Number;
use TallStackUi\Components\Form\Select\Native\Component as Native;
use TallStackUi\Components\Form\Select\Styled\Component as Styled;
use TallStackUi\Components\Form\Tag\Component as Tag;
use TallStackUi\Components\Form\Textarea\Component as Textarea;
use TallStackUi\Components\Traits\FormDefaultInputClasses;

describe('DefaultInputClasses', function () {
    test('should be used only specific components')
        ->expect(FormDefaultInputClasses::class)
        ->toOnlyBeUsedIn([
            Input::class,
            InputSelect::class,
            Number::class,
            Tag::class,
            Textarea::class,
            Native::class,
            Styled::class,
            Clipboard::class,
        ]);

    test('should have methods', function (string $method) {
        expect(FormDefaultInputClasses::class)->toHaveMethod($method);
    })->with([
        'error',
        'input',
    ]);
});
