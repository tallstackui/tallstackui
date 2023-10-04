<?php

namespace TasteUi\Support\Personalizations\Components\Tabs;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Tabs\Item as Component;

class Item extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
