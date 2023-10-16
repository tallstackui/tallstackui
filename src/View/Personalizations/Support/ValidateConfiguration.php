<?php

namespace TallStackUi\View\Personalizations\Support;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

/**
 * @internal This class is not meant to be used directly.
 */
class ValidateConfiguration
{
    /** @throws Exception */
    public static function from(object $component): void
    {
        $class = new self();

        (match (get_class($component)) {
            Dialog::class => fn () => $class->dialog(),
            Toast::class => fn () => $class->toast(),
            default => throw new Exception('No validation available for this component'),
        })();
    }

    private function dialog(): void
    {
        $configuration = config('tallstackui.personalizations.dialog');

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The dialog z-index must start with z- prefix.');
        }
    }

    private function toast(): void
    {
        $configuration = config('tallstackui.personalizations.toast');

        if (! in_array($configuration['position'], ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            throw new InvalidArgumentException("The toast position must be one of the following: ['top-right', 'top-left', 'bottom-right', 'bottom-left']");
        }

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix.');
        }
    }
}
