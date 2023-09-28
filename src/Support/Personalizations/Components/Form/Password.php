<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Password implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base',
        'icon.wrapper',
        'icon.classes',
        'error',
    ];
}
