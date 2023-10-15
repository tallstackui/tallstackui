<?php

namespace TallStackUi\View\Personalizations\Components\Wrapper;

use TallStackUi\View\Components\Wrapper\Select as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Select extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
