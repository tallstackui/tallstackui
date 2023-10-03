<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Modal as Component;

/**
 * @method $this wrapperFirst(string|Closure|Customizable $code)
 * @method $this wrapperSecond(string|Closure|Customizable $code)
 * @method $this wrapperThird(string|Closure|Customizable $code)
 * @method $this wrapperFourth(string|Closure|Customizable $code)
 * @method $this titleWrapper(string|Closure|Customizable $code)
 * @method $this titleBase(string|Closure|Customizable $code)
 * @method $this titleClose(string|Closure|Customizable $code)
 * @method $this body(string|Closure|Customizable $code)
 * @method $this footer(string|Closure|Customizable $code)
 */
class Modal extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
