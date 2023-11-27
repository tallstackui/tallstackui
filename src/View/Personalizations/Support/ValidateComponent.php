<?php

namespace TallStackUi\View\Personalizations\Support;

use Exception;
use InvalidArgumentException;
use TallStackUi\View\Components\Dropdown\Dropdown;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Select\Styled;
use TallStackUi\View\Components\Slide;
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
        $method = match (get_class($component)) {
            Dialog::class => 'dialog',
            Dropdown::class => 'dropdown',
            Errors::class => 'errors',
            Toast::class => 'toast',
            Styled::class => 'select',
            Slide::class => 'slide',
            Icon::class => 'icon',
            Modal::class => 'modal',
            Tooltip::class => 'tooltip',
            default => throw new Exception("No validation available for the component: [$component]"),
        };

        (new self())->{$method}($component);
    }

    /** @throws Throwable */
    public function modal(Modal $component): void
    {
        if (is_string($component->wire) && empty($component->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = config('tallstackui.settings.modal');

        $size = $component->size ?? $configuration['size'];
        $zIndex = $component->zIndex ?? $configuration['z-index'];

        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

        if (! in_array($size, $sizes)) {
            throw new InvalidArgumentException('The modal size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str_starts_with($zIndex, 'z-')) {
            throw new InvalidArgumentException('The modal z-index must start with z- prefix');
        }
    }

    /** @throws Throwable */
    public function select(Styled $component): void
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
    private function errors(Errors $component): void
    {
        throw_if(blank($component->title), new Exception('The [title] cannot be empty'));
    }

    /** @throws Throwable */
    private function icon(Icon $component): void
    {
        if (! in_array($component->type, ['solid', 'outline'])) {
            throw new InvalidArgumentException('The icon must be one of the following: [solid, outline]');
        }
    }

    private function slide(Slide $component): void
    {
        if (is_string($component->wire) && empty($component->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = config('tallstackui.settings.slide');

        $size = $component->size ?? $configuration['size'];
        $zIndex = $component->zIndex ?? $configuration['z-index'];
        $position = $component->left ? 'left' : $configuration['position'];

        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['right', 'left'];

        if (! in_array($size, $sizes)) {
            throw new InvalidArgumentException('The slide size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str_starts_with($zIndex, 'z-')) {
            throw new InvalidArgumentException('The slide z-index must start with z- prefix');
        }

        if (! in_array($position, $positions)) {
            throw new InvalidArgumentException('The slide position must be one of the following: ['.implode(', ', $positions).']');
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
        $positions = ['top', 'bottom', 'right', 'left'];

        if (! in_array($component->position, $positions)) {
            throw new InvalidArgumentException('The tooltip position must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
