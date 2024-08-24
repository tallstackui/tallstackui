<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TagRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'value' => __ts_sanitize_value($this->data['attributes']?->get('value'), $property, $this->livewire !== null),
        ];
    }
}
