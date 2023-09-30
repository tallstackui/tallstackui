<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Errors implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base.wrapper.first',
        'base.wrapper.second',
        'title.base',
        'title.wrapper',
        'body.list',
        'body.wrapper',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Errors::class;
    }
}
