<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use Closure;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Wrapper\Radio as Component;
use TasteUi\Contracts\Personalizable as Customizable;
/**
 * @method $this wrapper(string|Closure|Customizable $code)
 * @method $this labelSpan(string|Closure|Customizable $code)
 * @method $this labelBaseNormal(string|Closure|Customizable $code)
 * @method $this labelBaseError(string|Closure|Customizable $code)
 * @method $this slot(string|Closure|Customizable $code)
 */
class Radio extends Resource implements Personalizable
{

    protected function component(): string
    {
        return Component::class;
    }
}
