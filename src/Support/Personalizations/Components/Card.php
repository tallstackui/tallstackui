<?php

namespace TasteUi\Support\Personalizations\Components;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Card implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base',
        'wrapper.first',
        'wrapper.second',
        'title.wrapper',
        'title.text',
        'footer.wrapper',
        'footer.text',
    ];
}
