<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use TallStackUi\View\Components\Modal;

class ModalValidations
{
    public function __invoke(Modal $modal): void
    {
        if (is_string($modal->wire) && empty($modal->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }
    }
}
