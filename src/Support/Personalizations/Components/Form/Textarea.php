<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;
use TasteUi\View\Components\Form\Textarea as Component;

class Textarea extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
