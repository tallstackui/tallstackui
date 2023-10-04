<?php

namespace TasteUi\Support\Personalizations\Components\Tabs;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Tabs\Index as Component;

class Index extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
