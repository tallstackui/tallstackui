<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Form\Checkbox as Component;

class Checkbox extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
