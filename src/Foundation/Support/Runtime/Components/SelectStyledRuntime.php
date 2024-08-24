<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class SelectStyledRuntime extends AbstractRuntime
{
    // TODO: refactor
    public function runtime(): array
    {
        $bind = $this->bind();
        $livewire = $this->livewire !== null;

        /** @var WireDirective|null $wire */
        $wire = $livewire ? $this->data['attributes']->wire('change') : null;
        $change = [];

        if ($livewire && $wire && ($method = $wire->value()) !== null) {
            $change = [
                'id' => $this->livewire->getId(),
                'method' => $method,
            ];
        }

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'value' => __ts_sanitize_value($this->data['attributes']?->get('value'), $property, $livewire),
            'change' => [...$change],
            'disabled' => (bool) $this->data['attributes']->get('disabled', $this->data['attributes']->get('readonly', false)) === true,
        ];
    }
}
