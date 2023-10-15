<?php

namespace TallStackUi\View\Personalizations\Components;

use TallStackUi\View\Components\Tooltip as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Tooltip extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}