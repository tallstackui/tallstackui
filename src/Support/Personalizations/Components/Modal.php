<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Modal implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper.first',
        'wrapper.second',
        'wrapper.third',
        'wrapper.fourth',
        'title.wrapper',
        'title.base',
        'body',
        'footer',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Modal::class;
    }
}
