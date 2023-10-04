<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Select\Styled as Component;

class Styled extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
