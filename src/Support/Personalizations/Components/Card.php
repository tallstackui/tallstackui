<?php

namespace TasteUi\Support\Personalizations\Components;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Card implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'main',
        'wrapper.first',
        'wrapper.second',
        'title.wrapper',
        'title.text',
        'footer.wrapper',
        'footer.text',
    ];
}
