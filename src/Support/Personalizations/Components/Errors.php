<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Errors as Component;

/**
 * @method $this baseWrapperFirst(string|Closure|Customizable $code)
 * @method $this baseWrapperSecond(string|Closure|Customizable $code)
 * @method $this titleBase(string|Closure|Customizable $code)
 * @method $this titleWrapper(string|Closure|Customizable $code)
 * @method $this bodyList(string|Closure|Customizable $code)
 * @method $this bodyWrapper(string|Closure|Customizable $code)
 */
class Errors extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
