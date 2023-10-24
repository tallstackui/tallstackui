<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;

class ToastValidations
{
    public function __invoke(): void
    {
        $configuration = config('tallstackui.personalizations.toast');

        if (! in_array($configuration['position'], ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            throw new InvalidArgumentException("The toast position must be one of the following: ['top-right', 'top-left', 'bottom-right', 'bottom-left']");
        }

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix');
        }
    }
}
