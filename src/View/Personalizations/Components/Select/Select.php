<?php

namespace TallStackUi\View\Personalizations\Components\Select;

use TallStackUi\View\Components\Select\Select as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Select extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
