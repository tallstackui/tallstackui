<?php

namespace TallStackUi\View\Personalizations\Traits;

use TallStackUi\View\Personalizations\Support\ValidateComponent;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithValidations
{
    private function validate(): void
    {
        ValidateComponent::validate($this);
    }
}
