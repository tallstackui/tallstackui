<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class PinRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'hash' => $this->wireable() ? $this->livewire->getId().'-'.$property : uniqid(),
            'change' => $this->change(),
        ];
    }
}
