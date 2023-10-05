<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;
use TasteUi\View\Components\Interaction\Dialog as Component;

class Dialog extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
