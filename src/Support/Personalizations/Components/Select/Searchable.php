<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use TasteUi\Support\Personalizations\Components\PersonalizationResource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Select\Searchable as Component;

class Searchable extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
