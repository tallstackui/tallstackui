<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use InvalidArgumentException;
use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

// TODO: refactor
class TimeRuntime extends AbstractRuntime
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

        $data = [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'value' => $value = __ts_sanitize_value($this->data['attributes']?->get('value'), $property, $livewire),
            'change' => [...$change],
        ];

        $value = $livewire && property_exists($this->livewire, $property)
            ? data_get($this->livewire, $property)
            : $value;

        if (filled($value)) {
            $this->validate($value);
        }

        return $data;
    }

    private function validate(array|string $value): void
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The time [value] must be a string.');
        }
    }
}
