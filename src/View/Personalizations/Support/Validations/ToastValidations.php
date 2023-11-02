<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use Throwable;

class ToastValidations
{
    private const POSITIONS = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];

    /** @throws Throwable */
    public function __invoke(): void
    {
        $configuration = config('tallstackui.personalizations.toast');

        if (! in_array($configuration['position'], $positions = self::POSITIONS)) {
            throw new InvalidArgumentException('The toast position must be one of the following: ['.implode(', ', $positions).']');
        }

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix');
        }
    }
}
