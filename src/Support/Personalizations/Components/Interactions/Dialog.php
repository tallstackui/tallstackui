<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Interaction\Dialog as Component;

class Dialog extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
