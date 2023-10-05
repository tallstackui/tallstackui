<?php

namespace TasteUi\Support\Personalizations\Components\Tabs;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;
use TasteUi\View\Components\Tabs\Item as Component;

class Item extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
