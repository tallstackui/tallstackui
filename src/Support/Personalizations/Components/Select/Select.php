<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Select implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = ['base', 'error'];

    public function component(): string
    {
        return \TasteUi\View\Components\Select\Select::class;
    }
}
