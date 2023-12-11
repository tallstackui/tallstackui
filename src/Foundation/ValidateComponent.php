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
    private function dialog(): void
    {
        $configuration = config('tallstackui.settings.dialog');

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The dialog z-index must start with z- prefix');
        }
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

    /** @throws Throwable */
    private function select(Styled $component): void
    {
        throw_if(blank($component->placeholders['default']), new Exception('The placeholder [default] cannot be empty.'));
        throw_if(blank($component->placeholders['search']), new Exception('The placeholder [search] cannot be empty.'));
        throw_if(blank($component->placeholders['empty']), new Exception('The placeholder [empty] cannot be empty.'));

        if ($component->ignoreValidations) {
            return;
        }

        if (filled($component->options) && filled($component->request)) {
            throw new InvalidArgumentException('You cannot define [options] and [request] at the same time.');
        }

        if (($component->common && isset($component->options[0]) && (is_array($component->options[0]) && ! $component->select)) || ! $component->common && ! $component->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }

        if ($component->common || $component->request && ! is_array($component->request)) {
            return;
        }

        if (! isset($component->request['url'])) {
            throw new InvalidArgumentException('The [url] is required in the request array');
        }

        $component->request['method'] ??= 'get';
        $component->request['method'] = strtolower($component->request['method']);

        if (! in_array($component->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [method] must be get or post');
        }

        if (! isset($component->request['params'])) {
            return;
        }

        if (! is_array($component->request['params']) || blank($component->request['params'])) {
            throw new InvalidArgumentException('The [params] must be an array and cannot be empty');
        }
    }

    /** @throws Throwable */
    private function toast(): void
    {
        $configuration = config('tallstackui.settings.toast');
        $positions = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];

        if (! in_array($configuration['position'], $positions)) {
            throw new InvalidArgumentException('The toast position must be one of the following: ['.implode(', ', $positions).']');
        }

        if (! str_starts_with($configuration['z-index'], 'z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix');
        }
    }

    /** @throws Throwable */
    private function tooltip(Tooltip $component): void
    {
        $positions = [
            'top',
            'top-start',
            'top-end',
            'bottom',
            'bottom-start',
            'bottom-end',
            'left',
            'left-start',
            'left-end',
            'right',
            'right-start',
            'right-end',
            'auto',
            'auto-start',
            'auto-end',
        ];

        if (! in_array($component->position, $positions)) {
            throw new InvalidArgumentException('The tooltip position must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
