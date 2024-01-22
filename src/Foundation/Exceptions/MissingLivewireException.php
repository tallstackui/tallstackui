<?php

namespace TallStackUi\Foundation\Exceptions;

use Exception;

class MissingLivewireException extends Exception
{
    public function __construct(string $component)
    {
        parent::__construct('The ['.$component.'] component should only be used inside Livewire components.');
    }

    /** @throws MissingLivewireException */
    public static function throwIf(bool $livewire, string $component): ?self
    {
        if ($livewire) {
            return null;
        }

        throw new self($component);
    }
}
