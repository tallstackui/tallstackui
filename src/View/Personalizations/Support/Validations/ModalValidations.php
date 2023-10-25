<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use InvalidArgumentException;
use TallStackUi\View\Components\Modal;

class ModalValidations
{
    private const SIZES = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

    public function __invoke(Modal $modal): void
    {
        if (is_string($modal->wire) && empty($modal->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = config('tallstackui.personalizations.modal');

        $size = $modal->size ?? $configuration['size'];
        $zIndex = $modal->zIndex ?? $configuration['z-index'];

        if (! in_array($size, $sizes = self::SIZES)) {
            throw new InvalidArgumentException('The modal size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str_starts_with($zIndex, 'z-')) {
            throw new InvalidArgumentException('The modal z-index must start with z- prefix');
        }
    }
}
