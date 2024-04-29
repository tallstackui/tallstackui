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
    public static function throwIf(bool $boolean, string $component): ?self
    {
        if ($boolean) {
            return null;
        }

        throw new self($component);
    }

    /** @throws MissingLivewireException */
    public static function throwUnless(bool $boolean, string $component): ?self
    {
        if (! $boolean) {
            return null;
        }

        throw new self($component);
    }
}
