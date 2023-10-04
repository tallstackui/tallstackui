<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Wrapper\Radio as Component;

class Radio extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
