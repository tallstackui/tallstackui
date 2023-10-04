<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Select\Select as Component;

class Select extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
