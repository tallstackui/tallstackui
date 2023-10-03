<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Card as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 * @method $this wrapperFirst(string|Closure|Customizable $code)
 * @method $this wrapperSecond(string|Closure|Customizable $code)
 * @method $this titleWrapper(string|Closure|Customizable $code)
 * @method $this titleText(string|Closure|Customizable $code)
 * @method $this footerWrapper(string|Closure|Customizable $code)
 * @method $this footerText(string|Closure|Customizable $code)
 */
class Card extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
