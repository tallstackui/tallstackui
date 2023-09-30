<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Label implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = [
        'wrapper',
        'text',
        'error',
    ];

    public function component(): string
    {
        return \TasteUi\View\Components\Form\Label::class;
    }
}
