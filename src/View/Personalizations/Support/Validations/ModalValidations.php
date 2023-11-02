<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use TallStackUi\View\Components\Modal;
use Throwable;

class ModalValidations
{
    private const SIZES = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

    /** @throws Throwable */
    public function __invoke(Modal $component): void
    {
        if (is_string($component->wire) && empty($component->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = config('tallstackui.personalizations.modal');

        $size = $component->size ?? $configuration['size'];
        $zIndex = $component->zIndex ?? $configuration['z-index'];

        if (! in_array($size, $sizes = self::SIZES)) {
            throw new InvalidArgumentException('The modal size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str_starts_with($zIndex, 'z-')) {
            throw new InvalidArgumentException('The modal z-index must start with z- prefix');
        }
    }
}
