<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class UploadRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $data = [
            ...$this->locks(),
            'id' => $bind->get('id'),
            'property' => $property = $bind->get('property'),
            'validate' => $validate = $bind->get('validate'),
            // We can get this directly - without need to check if we're in Livewire
            // context because this component is only used in Livewire context.
            'value' => $value = $this->property($property),
            'invalid' => [
                'status' => $status = $bind->get('error'),
                'summary' => $validate && ! $status && ! $this->data('withoutSummary') && $this->errors->has($property.'.*'),
                'quantity' => count($this->errors->get(is_array($value) ? $property.'.*' : $property)),
            ],
        ];

        if (is_null($property)) {
            __ts_validation_exception($this->component, 'The component requires a property to bind using [wire:model].');
        }

        return $data;
    }
}
