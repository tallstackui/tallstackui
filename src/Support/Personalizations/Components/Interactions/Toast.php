<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Toast implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper.first',
        'wrapper.second',
        'wrapper.third',
        'wrapper.fourth',
        'icon.size',
        'content.wrapper',
        'content.text',
        'content.description',
        'buttons.wrapper',
        'buttons.confirm',
        'buttons.cancel.wrapper',
        'buttons.cancel.base',
        'buttons.cancel.size',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Interactions\Toast::class;
    }
}
