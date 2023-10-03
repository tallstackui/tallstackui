<?php

namespace TasteUi\Support\Personalizations\Components\Wrapper;

use Closure;
use TasteUi\Support\Personalizations\Components\Resource;
use TasteUi\Support\Personalizations\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Traits\ShareablePersonalization;
use TasteUi\View\Components\Wrapper\Select as Component;
use TasteUi\Contracts\Personalizable as Customizable;
/**
 * @method $this wrapper(string|Closure|Customizable $code)
 * @method $this divBase(string|Closure|Customizable $code)
 * @method $this divError(string|Closure|Customizable $code)
 * @method $this header(string|Closure|Customizable $code)
 * @method $this buttonsWrapper(string|Closure|Customizable $code)
 * @method $this buttonsMarkBase(string|Closure|Customizable $code)
 * @method $this buttonsMarkError(string|Closure|Customizable $code)
 * @method $this buttonsDownBase(string|Closure|Customizable $code)
 * @method $this buttonsDownError(string|Closure|Customizable $code)
 * @method $this boxWrapper(string|Closure|Customizable $code)
 * @method $this boxButtonBase(string|Closure|Customizable $code)
 * @method $this boxButtonIcon(string|Closure|Customizable $code)
 * @method $this boxListWrapper(string|Closure|Customizable $code)
 * @method $this boxListLoadingWrapper(string|Closure|Customizable $code)
 * @method $this boxListLoadingBase(string|Closure|Customizable $code)
 * @method $this boxListItemWrapper(string|Closure|Customizable $code)
 * @method $this boxListItemBase(string|Closure|Customizable $code)
 * @method $this message(string|Closure|Customizable $code)
 */
class Select extends Resource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
