<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Illuminate\Contracts\Support\Arrayable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Textarea implements Arrayable, Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = ['base', 'error'];

    public function component(): string
    {
        return \TasteUi\View\Components\Form\Textarea::class;
    }
}
