<?php

namespace TasteUi\Support\Personalizations\Components;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Tooltip implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper',
        'icon',
    ];
}
