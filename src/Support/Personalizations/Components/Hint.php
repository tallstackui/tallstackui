<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Hint implements Personalizable
{
    use ShareablePersonalization;

    public function component(): string
    {
        return \TasteUi\View\Components\Hint::class;
    }
}
