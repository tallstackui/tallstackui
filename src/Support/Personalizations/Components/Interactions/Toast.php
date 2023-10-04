<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Interaction\Toast as Component;

class Toast extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
