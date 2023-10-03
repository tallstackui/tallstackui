<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Select\Select as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 * @method $this error(string|Closure|Customizable $code)
 */
class Select extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
