<?php

namespace TasteUi\Support\Personalizations;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Alert implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'main',
        'title.base',
        'title.wrapper',
        'title.icon.wrapper',
        'title.icon.classes',
        'text.wrapper',
        'text.title.wrapper',
        'text.icon.wrapper',
        'text.icon.classes',
    ];
}
