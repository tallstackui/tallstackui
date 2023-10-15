<?php

namespace TallStackUi\View\Personalizations\Providers;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

class ValidationProvider
{
    /** @throws Exception */
    public static function resolve(object $component, ...$parameters): void
    {
        $class = new static();

        (match (get_class($component) ?? null) {
            Dialog::class => fn () => $class->dialog($parameters),
            Toast::class => fn () => $class->toast($parameters),
            default => throw new Exception('No validation available for this component'),
        })();
    }

    private function dialog(array $parameters): void
    {
        if (! str_starts_with($parameters['z-index'], 'z-')) {
            throw new InvalidArgumentException('The dialog z-index must start with z- prefix.');
        }
    }

    private function toast(array $parameters): void
    {
        if (! in_array($parameters['position'], ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            throw new InvalidArgumentException("The toast position must be one of the following: ['top-right', 'top-left', 'bottom-right', 'bottom-left']");
        }

        if (! str_starts_with($parameters['z-index'], 'z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix.');
        }
    }
}
