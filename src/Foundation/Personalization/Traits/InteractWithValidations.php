<?php

namespace TallStackUi\Foundation\Personalization\Traits;

use Exception;
use TallStackUi\Foundation\ValidateComponent;

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
