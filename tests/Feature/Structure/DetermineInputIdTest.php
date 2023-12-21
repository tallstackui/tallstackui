<?php

use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Hint;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Label;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Pin;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Traits\DetermineInputId;
use TallStackUi\View\Components\Wrapper\Input as InputWrapper;
use TallStackUi\View\Components\Wrapper\Radio as RadioWrapper;

describe('DetermineInputId', function () {
    test('should use the trait')
        ->expect([
            Input::class,
            Password::class,
            Textarea::class,
            Radio::class,
            Range::class,
            Checkbox::class,
            Toggle::class,
            Color::class,
        ])->toUse(DetermineInputId::class);

    test('should not use the trait')
        ->expect([
            Pin::class,
            Error::class,
            Label::class,
            Hint::class,
            InputWrapper::class,
            RadioWrapper::class,
        ])->not->toUse(DetermineInputId::class);

    it('should return the correct id based on id')
        ->expect('<x-input id="foo-bar-baz" />')
        ->render()
        ->toContain('id="foo-bar-baz"');

    it('should return the correct id based on name')
        ->expect('<x-input name="foo-bar-baz" />')
        ->render()
        ->toContain('id="foo-bar-baz"');
});
