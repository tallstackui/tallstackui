<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;

class Radio implements Personalizable
{
    use ShareablePersonalization;

    public const EDITABLES = ['base', 'error'];

    public function component(): string
    {
        return \TasteUi\View\Components\Form\Radio::class;
    }
}
