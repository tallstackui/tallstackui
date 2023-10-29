<?php

namespace TallStackUi\View\Personalizations\Traits;

use Exception;
use TallStackUi\View\Personalizations\Support\ValidateComponent;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithValidations
{
    /** @throws Exception */
    private function validate(): void
    {
        ValidateComponent::validate($this);
    }
}
