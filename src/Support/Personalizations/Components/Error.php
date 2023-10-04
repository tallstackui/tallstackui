<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Error as Component;

class Error extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
