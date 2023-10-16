<?php

namespace TallStackUi\View\Personalizations\Traits;

use TallStackUi\View\Personalizations\Support\ValidateConfiguration;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithValidations
{
    private function validate(): void
    {
        ValidateConfiguration::from($this);
    }
}
