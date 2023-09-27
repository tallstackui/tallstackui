<?php

namespace TasteUi\Support\Personalizations\Components\Button;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\ShouldBePersonalized;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Index implements Arrayable, ShouldBePersonalized
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'main.wrapper',
        'main.icon',
    ];
}
