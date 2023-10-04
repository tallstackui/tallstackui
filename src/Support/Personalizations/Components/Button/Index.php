<?php

namespace TasteUi\Support\Personalizations\Components\Button;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Button\Index as Component;

class Index extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
