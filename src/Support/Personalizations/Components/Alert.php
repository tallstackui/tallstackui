<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Alert implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base',
        'title.base',
        'title.wrapper',
        'title.icon.wrapper',
        'title.icon.classes',
        'text.wrapper',
        'text.title.wrapper',
        'text.title.icon.wrapper',
        'text.title.icon.classes',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Alert::class;
    }
}
