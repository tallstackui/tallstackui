<?php

namespace TallStackUi\View\Personalizations\Components\Form;

use TallStackUi\View\Components\Form\Radio as Component;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Radio extends PersonalizationResource implements PersonalizableResources
{
    protected function component(): string
    {
        return Component::class;
    }
}
