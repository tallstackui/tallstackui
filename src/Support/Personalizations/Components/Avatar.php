<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Avatar\Index as Component;

/**
 * @method $this wrapper(string|Closure|Customizable $code)
 * @method $this content(string|Closure|Customizable $code)
 */
class Avatar extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
