<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Badge as Component;

class Badge extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
