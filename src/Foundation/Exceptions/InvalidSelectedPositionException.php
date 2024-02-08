<?php

namespace TallStackUi\Foundation\Exceptions;

use Exception;

class InvalidSelectedPositionException extends Exception
{
    private const ALLOWED = [
        'auto',
        'auto-start',
        'auto-end',
        'bottom',
        'bottom-start',
        'bottom-end',
        'left',
        'left-start',
        'left-end',
        'right',
        'right-start',
        'right-end',
        'top',
        'top-start',
        'top-end',
    ];

    public function __construct(string $component)
    {
        parent::__construct('The '.$component.' [position] must be one of the following: ['.implode(', ', self::ALLOWED).']');
    }

    /** @throws InvalidSelectedPositionException */
    public static function validate(string $component, ?string $position = null): ?self
    {
        $component = str($component)->afterLast('\\')->lower()->value();

        if (! $position || in_array($position, self::ALLOWED)) {
            return null;
        }

        throw new self($component);
    }
}
