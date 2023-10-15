<?php

namespace TallStackUi\View\Personalizations\Components\Interactions;

use TallStackUi\View\Components\Interaction\Dialog as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Dialog extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
