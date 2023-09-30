<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Badge implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = ['base', 'icon'];

    public function component(): string
    {
        return \TasteUi\View\Components\Badge::class;
    }
}
