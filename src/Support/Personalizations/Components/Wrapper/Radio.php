<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\PersonalizationResource;
use TasteUi\View\Components\Wrapper\Radio as Component;

class Radio extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
