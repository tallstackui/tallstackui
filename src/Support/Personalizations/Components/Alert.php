<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Alert as Component;

class Alert extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
