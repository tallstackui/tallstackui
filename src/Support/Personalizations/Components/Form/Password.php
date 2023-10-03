<?php

namespace TasteUi\Support\Personalizations\Components\Form;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Form\Password as Component;

/**
 * @method $this base(string|Closure|Customizable $code)
 * @method $this iconWrapper(string|Closure|Customizable $code)
 * @method $this iconClass(string|Closure|Customizable $code)
 * @method $this error(string|Closure|Customizable $code)
 */
class Password extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
