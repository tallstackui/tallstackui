<?php

namespace TallStackUi\View\Personalizations\Components\Form;

use TallStackUi\View\Components\Form\Checkbox as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Checkbox extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
