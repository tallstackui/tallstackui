<?php

namespace TallStackUi\Support\Personalizations\Traits;

use Exception;
use TallStackUi\Support\ValidateComponent;

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
