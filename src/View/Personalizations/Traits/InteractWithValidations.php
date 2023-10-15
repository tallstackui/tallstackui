<?php

namespace TallStackUi\View\Personalizations\Traits;

use TallStackUi\View\Personalizations\Support\Validation;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithValidations
{
    private function validate(): void
    {
        Validation::from($this);
    }
}
