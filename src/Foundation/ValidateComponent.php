<?php

namespace TallStackUi\Foundation;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Dropdown\Dropdown;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Select\Styled;
use TallStackUi\View\Components\Tooltip;
use Throwable;

/**
 * @internal This class is not meant to be used directly.
 */
class ValidateComponent
{
    /** @throws Exception */
    public static function validate(object $component): void
    {
        $name = get_class($component);

        $method = match ($name) {
            Dialog::class => 'dialog',
            Dropdown::class => 'dropdown',
            Styled::class => 'select',
            Toast::class => 'toast',
            Tooltip::class => 'tooltip',
            Radio::class, Checkbox::class, Toggle::class => 'radio',
            default => throw new Exception("No validation available for the component: [$name]"),
        };

        (new self())->{$method}($component);
    }

    /** @throws Throwable */
    private function dropdown(Dropdown $component): void
    {
        $positions = ['bottom', 'bottom-start', 'bottom-end', 'top', 'top-start', 'top-end', 'left', 'left-start', 'left-end', 'right', 'right-start', 'right-end'];

        if (! in_array($component->position, $positions)) {
            throw new InvalidArgumentException('The dropdown position must be one of the following: ['.implode(', ', $positions).']');
        }
    }

    /** @throws Throwable */
    private function radio(Radio|Checkbox|Toggle $component): void
    {
        $positions = ['right', 'left'];

        if (! in_array($component->position, $positions)) {
            throw new InvalidArgumentException('The [position] must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
