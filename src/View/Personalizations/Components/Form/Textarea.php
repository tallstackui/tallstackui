<?php

namespace TallStackUi\View\Personalizations\Components\Form;

use TallStackUi\View\Components\Form\Textarea as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Textarea extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
