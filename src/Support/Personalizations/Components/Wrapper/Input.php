<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Wrapper\Input as Component;

class Input implements Personalizable
{
    use ShareablePersonalization;

    public function component(): string
    {
        return Component::class;
    }
}
