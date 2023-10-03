<?php

namespace TasteUi\Support\Personalizations\Components\Interactions;

use Closure;
use TasteUi\Contracts\Personalizable as Customizable;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\View\Components\Interaction\Dialog as Component;

/**
 * @method $this background(string|Closure|Customizable $code)
 * @method $this wrapperFirst(string|Closure|Customizable $code)
 * @method $this wrapperSecond(string|Closure|Customizable $code)
 * @method $this wrapperThird(string|Closure|Customizable $code)
 * @method $this iconWrapper(string|Closure|Customizable $code)
 * @method $this iconSize(string|Closure|Customizable $code)
 * @method $this textWrapper(string|Closure|Customizable $code)
 * @method $this textTitle(string|Closure|Customizable $code)
 * @method $this textDescriptionWrapper(string|Closure|Customizable $code)
 * @method $this textDescriptionText(string|Closure|Customizable $code)
 * @method $this buttonsWrapper(string|Closure|Customizable $code)
 * @method $this buttonsCancel(string|Closure|Customizable $code)
 * @method $this buttonsConfirm(string|Closure|Customizable $code)
 * @method $this buttonsCloseWrapper(string|Closure|Customizable $code)
 * @method $this buttonsCloseBase(string|Closure|Customizable $code)
 */
class Dialog extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
