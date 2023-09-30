<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Alert implements Personalizable
{
    use ShareablePersonalization;

    public function component(): string
    {
        return \TasteUi\View\Components\Alert::class;
    }
}
