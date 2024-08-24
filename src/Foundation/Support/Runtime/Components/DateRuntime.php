<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

// TODO: refactor
class DateRuntime extends AbstractRuntime
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
        $range = $this->data('range');
        $multiple = $this->data('multiple');

        if (($range || $multiple) && ! is_array($value)) {
            throw new InvalidArgumentException('The date [value] must be an array when using the [range] or [multiple].');
        }

        if ($range && count($value) === 2) {
            [$start, $end] = array_map(fn (string $date) => Carbon::parse($date), $value);

            if ($start->greaterThan($end)) {
                throw new InvalidArgumentException('The start date in the [range] must be greater than the second date.');
            }
        }
    }
}
