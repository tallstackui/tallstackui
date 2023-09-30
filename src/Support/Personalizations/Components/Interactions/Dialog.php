<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Dialog implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'background',
        'wrapper.first',
        'wrapper.second',
        'wrapper.third',
        'icon.wrapper',
        'icon.size',
        'text.wrapper',
        'text.title',
        'text.description.wrapper',
        'text.description.text',
        'buttons.wrapper',
        'buttons.cancel',
        'buttons.confirm',
        'buttons.close.wrapper',
        'buttons.close.base',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Interactions\Dialog::class;
    }
}
