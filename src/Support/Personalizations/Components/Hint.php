<?php

namespace TasteUi\Support\Personalizations\Components;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Hint as Component;

class Hint extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
