<?php

namespace TallStackUi\View\Personalizations\Components\Form;

use TallStackUi\View\Components\Form\Textarea as Component;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Textarea extends PersonalizationResource implements PersonalizableResources
{
    protected function component(): string
    {
        return Component::class;
    }
}
