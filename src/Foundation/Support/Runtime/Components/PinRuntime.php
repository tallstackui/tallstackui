<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

// TODO: refactor
class PinRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $bind = $this->bind();
        $livewire = $this->livewire !== null;

        /** @var WireDirective|null $wire */
        $wire = $this->data['attributes']->wire('change');
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
            'hash' => $livewire ? $this->livewire->getId().'-'.$property : uniqid(),
            'change' => [...$change],
        ];
    }
}
