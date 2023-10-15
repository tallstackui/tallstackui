<?php

namespace TallStackUi\View\Personalizations\Components\Tabs;

use TallStackUi\View\Components\Tab\Tab as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

class Tab extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
