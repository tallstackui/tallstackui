<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Avatar implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper',
        'content',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Avatar\Index::class;
    }
}
