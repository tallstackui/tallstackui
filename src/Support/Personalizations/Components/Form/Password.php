<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use TasteUi\Support\Personalizations\Components\PersonalizationResource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Form\Password as Component;

class Password extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
