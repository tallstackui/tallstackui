<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class RatingRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $property = $bind->get('entangle'),
            'tax' => $this->livewire !== null ? data_get($this->livewire, $property) : $this->data['rate'],
        ];
    }
}
