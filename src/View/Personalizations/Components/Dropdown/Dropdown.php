<?php

namespace TallStackUi\View\Personalizations\Components\Dropdown;

use TallStackUi\View\Components\Dropdown\Dropdown as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Dropdown extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
