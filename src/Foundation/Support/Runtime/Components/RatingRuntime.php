<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class RatingRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $property = $bind->get('entangle'),
            'tax' => $this->livewire !== null ? data_get($this->livewire, $property) : $this->data['rate'],
        ];
    }
}
