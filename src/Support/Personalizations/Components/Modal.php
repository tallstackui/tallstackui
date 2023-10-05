<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;
use TasteUi\View\Components\Modal as Component;

class Modal extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
