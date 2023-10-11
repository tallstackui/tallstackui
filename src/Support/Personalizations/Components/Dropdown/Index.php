<?php

namespace TallStackUi\Support\Personalizations\Components\Dropdown;

use TallStackUi\Support\Personalizations\Contracts\Personalizable;
use TallStackUi\Support\Personalizations\PersonalizationResource;
use TallStackUi\View\Components\Dropdown\Index as Component;

class Index extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
