<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Interaction\Toast as Component;

/**
 * @method $this wrapperFirst(string|Closure|Customizable $code)
 * @method $this wrapperSecond(string|Closure|Customizable $code)
 * @method $this wrapperThird(string|Closure|Customizable $code)
 * @method $this wrapperFourth(string|Closure|Customizable $code)
 * @method $this iconSize(string|Closure|Customizable $code)
 * @method $this contentWrapper(string|Closure|Customizable $code)
 * @method $this contentText(string|Closure|Customizable $code)
 * @method $this contentDescription(string|Closure|Customizable $code)
 * @method $this buttonsWrapper(string|Closure|Customizable $code)
 * @method $this buttonsConfirm(string|Closure|Customizable $code)
 * @method $this buttonsCancel(string|Closure|Customizable $code)
 * @method $this buttonsCloseWrapper(string|Closure|Customizable $code)
 * @method $this buttonsCloseBase(string|Closure|Customizable $code)
 * @method $this buttonsCloseSize(string|Closure|Customizable $code)
 */
class Toast extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
