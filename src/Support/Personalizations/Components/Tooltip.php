<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Tooltip implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper',
        'icon',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Tooltip::class;
    }
}
