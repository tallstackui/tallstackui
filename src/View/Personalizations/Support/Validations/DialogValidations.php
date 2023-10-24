<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;

class DialogValidations
{
    public function __invoke(): void
    {
        $configuration = config('tallstackui.personalizations.dialog');

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The dialog z-index must start with z- prefix');
        }
    }
}
