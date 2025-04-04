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
        $entangle = $bind->get('entangle');

        $this->validate($bind->get('property'));

        return ['entangle' => $entangle === 'null' ? ($this->data('rate') ?? 'null') : $entangle];
    }

    /** @throws Exception */
    private function validate(?string $property = null): void
    {
        if (! $this->livewire || ! $property) {
            return;
        }

        $method = $this->data('evaluateMethod');

        if ($this->data('static') === false && ! method_exists($this->livewire, $method)) {
            __ts_validation_exception($this->component, 'The ['.$method.'] must be a public method of the Livewire component.');
        }

        if ($this->data('rate') !== null) {
            __ts_validation_exception($this->component, 'The [rate] can be omitted because you are in Livewire context. You can use `wire:model` instead.');
        }

        if (($value = data_get($this->livewire, $property)) !== null && ! is_int($value)) {
            __ts_validation_exception($this->component, 'The [value] must be a int.');
        }
    }
}
