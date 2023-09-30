<?php

namespace TasteUi\Support\Personalizations\Components\Button;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Circle implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'base',
        'icon',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Button\Circle::class;
    }
}
