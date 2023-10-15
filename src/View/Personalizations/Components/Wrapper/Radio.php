<?php

namespace TallStackUi\View\Personalizations\Components\Wrapper;

use TallStackUi\View\Components\Wrapper\Radio as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Radio extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}
