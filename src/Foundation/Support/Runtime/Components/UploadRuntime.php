<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

// TODO: refactor
class UploadRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $data = [
            'property' => $property = $bind->get('property'),
            // We can get this directly - without need to check if we're in Livewire
            // context because this component is only used in Livewire context.
            'value' => $value = data_get($this->livewire, $property),
        ];

        if (is_null($property)) {
            throw new Exception('The [upload] component requires a property to bind using [wire:model].');
        }

        return $data;
    }
}
