<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Dialog implements Personalizable
{
    use ShareablePersonalization;

    public function component(): string
    {
        return \TasteUi\View\Components\Interactions\Dialog::class;
    }
}
