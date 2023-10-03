<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Alert as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 * @method $this titleBase(string|Closure|Customizable $code)
 * @method $this titleWrapper(string|Closure|Customizable $code)
 * @method $this titleIconWrapper(string|Closure|Customizable $code)
 * @method $this titleIconClasses(string|Closure|Customizable $code)
 * @method $this textWrapper(string|Closure|Customizable $code)
 * @method $this textTitleWrapper(string|Closure|Customizable $code)
 * @method $this textTitleIconWrapper(string|Closure|Customizable $code)
 * @method $this textTitleIconClasses(string|Closure|Customizable $code)
 */
class Alert extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
