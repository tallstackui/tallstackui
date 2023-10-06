<?php

namespace TallStackUi\Support\Personalizations\Components\Interactions;

use TallStackUi\Support\Personalizations\Contracts\Personalizable;
use TallStackUi\Support\Personalizations\PersonalizationResource;
use TallStackUi\View\Components\Interaction\Toast as Component;

class Toast extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
