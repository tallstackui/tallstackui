<?php

namespace TallStackUi\View\Personalizations\Components\Interactions;

use TallStackUi\View\Components\Interaction\Toast as Component;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Toast extends PersonalizationResource implements PersonalizableResources
{
    protected function component(): string
    {
        return Component::class;
    }
}
