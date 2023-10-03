<?php

namespace TasteUi\Support\Personalizations\Components\Select;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Select\Searchable as Component;

/**
 * @method $this multiple(string|Closure|Customizable $code)
 * @method $this icon(string|Closure|Customizable $code)
 */
class Searchable extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
