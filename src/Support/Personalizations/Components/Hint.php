<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Hint as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 */
class Hint extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
