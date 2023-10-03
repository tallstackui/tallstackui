<?php

namespace TasteUi\Support\Personalizations\Components\Button;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Button\Index as Component;

/**
 * @method $this wrapper(string|Closure|Customizable $code)
 * @method $this icon(string|Closure|Customizable $code)
 */
class Index extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
