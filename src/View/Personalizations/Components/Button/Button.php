<?php

namespace TallStackUi\View\Personalizations\Components\Button;

use TallStackUi\View\Components\Button\Button as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Button extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
