<?php

namespace TallStackUi\View\Personalizations\Components\Interactions;

use TallStackUi\View\Components\Interaction\Toast as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Toast extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
