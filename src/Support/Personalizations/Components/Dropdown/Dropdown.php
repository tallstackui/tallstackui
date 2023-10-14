<?php

namespace TallStackUi\Support\Personalizations\Components\Dropdown;

use TallStackUi\Support\Personalizations\Contracts\Personalizable;
use TallStackUi\Support\Personalizations\PersonalizationResource;
use TallStackUi\View\Components\Dropdown\Dropdown as Component;

class Dropdown extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
