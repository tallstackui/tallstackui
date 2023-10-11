<?php

namespace TallStackUi\Support\Personalizations\Components\Dropdown;

use TallStackUi\Support\Personalizations\Contracts\Personalizable;
use TallStackUi\Support\Personalizations\PersonalizationResource;
use TallStackUi\View\Components\Dropdown\Items as Component;

class Items extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
