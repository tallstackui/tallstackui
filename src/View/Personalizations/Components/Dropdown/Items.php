<?php

namespace TallStackUi\View\Personalizations\Components\Dropdown;

use TallStackUi\View\Components\Dropdown\Items as Component;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Items extends PersonalizationResource implements PersonalizableResources
{
    protected function component(): string
    {
        return Component::class;
    }
}
