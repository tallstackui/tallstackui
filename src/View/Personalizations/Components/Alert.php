<?php

namespace TallStackUi\View\Personalizations\Components;

use TallStackUi\View\Components\Alert as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Alert extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
