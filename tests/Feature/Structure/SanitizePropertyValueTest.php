<?php

use TallStackUi\Foundation\Traits\SanitizePropertyValue;
use TallStackUi\View\Components\Form\Date;
use TallStackUi\View\Components\Form\Tag;
use TallStackUi\View\Components\Select\Styled;

describe('SanitizePropertyValue', function () {
    test('trait has method', function () {
        expect(SanitizePropertyValue::class)->toHaveMethod('sanitize');
    });

    test('trait should only be used in', function () {
        expect(SanitizePropertyValue::class)
            ->toOnlyBeUsedIn([Date::class, Tag::class, Styled::class]);
    });
});
