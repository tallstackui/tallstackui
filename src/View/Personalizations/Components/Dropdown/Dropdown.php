<?php

namespace TallStackUi\View\Personalizations\Components\Dropdown;

use TallStackUi\View\Components\Dropdown\Dropdown as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Dropdown extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
