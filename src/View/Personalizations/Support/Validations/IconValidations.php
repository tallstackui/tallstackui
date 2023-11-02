<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use TallStackUi\View\Components\Icon;
use Throwable;

class IconValidations
{
    /** @throws Throwable */
    public function __invoke(Icon $component): void
    {
        if (! in_array($component->type, ['solid', 'outline'])) {
            throw new InvalidArgumentException('The icon must be one of the following: [solid, outline]');
        }
    }
}
