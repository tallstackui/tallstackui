<?php

namespace TasteUi\Support\Personalizations\Components;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Errors implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base.wrapper.first',
        'base.wrapper.second',
        'title.base',
        'title.wrapper',
        'body.list',
        'body.wrapper',
    ];
}
