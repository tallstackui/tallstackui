<?php

namespace TasteUi\Support\Personalizations;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Errors implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'main.base',
        'main.wrapper',
        'title.base',
        'title.wrapper',
        'body.lists',
        'body.wrapper',
    ];
}
