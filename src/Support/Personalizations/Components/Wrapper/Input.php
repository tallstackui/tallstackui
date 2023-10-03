<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Wrapper\Input as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 */
class Input extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
