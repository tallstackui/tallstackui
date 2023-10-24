<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use TallStackUi\View\Components\Icon;

class IconValidations
{
    public function __invoke(Icon $icon): void
    {
        if (! in_array($icon->type, ['solid', 'outline'])) {
            throw new InvalidArgumentException('The icon must be one of the following: [solid, outline]');
        }
    }
}
