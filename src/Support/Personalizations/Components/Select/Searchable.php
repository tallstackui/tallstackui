<?php

namespace TallStackUi\Support\Personalizations\Components\Select;

use TallStackUi\Support\Personalizations\Contracts\Personalizable;
use TallStackUi\Support\Personalizations\PersonalizationResource;
use TallStackUi\View\Components\Select\Searchable as Component;

class Searchable extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
