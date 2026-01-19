<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class ReactionRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        return [
            ...$this->bind()->only('entangle'),
            'extension' => $this->data('animated') === true ? 'gif' : 'png',
            'id' => $this->livewire->getId(),
        ];
    }
}
