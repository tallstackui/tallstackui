<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Form\Radio as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 * @method $this error(string|Closure|Customizable $code)
 */
class Radio extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
