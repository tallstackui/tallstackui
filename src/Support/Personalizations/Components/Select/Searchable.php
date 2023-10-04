<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Select\Searchable as Component;

class Searchable extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
