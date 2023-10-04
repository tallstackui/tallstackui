<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Form\Label as Component;

class Label extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
