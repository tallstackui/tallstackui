<?php

namespace TasteUi\Support\Personalizations\Components;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Alert implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base',
        'title.base',
        'title.wrapper',
        'title.icon.wrapper',
        'title.icon.classes',
        'text.wrapper',
        'text.title.wrapper',
        'text.title.icon.wrapper',
        'text.title.icon.classes',
    ];
}
