<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class RangeRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $data = [
            ...$bind->only('property', 'error', 'id')->all(),
            ...$this->locks(),
        ];

        if (! $this->data('dual')) {
            return $data;
        }

        [$min, $max] = [$this->data('min'), $this->data('max')];

        $this->boundaries($min, $max, $this->data('step'));

        return [
            ...$data,
            'entangle' => $bind->get('entangle'),
            'initial' => $this->initial($this->value($bind->get('property'), $this->data('value')), $min, $max),
        ];
    }

    private function boundaries(mixed $min, mixed $max, mixed $step): void
    {
        if ($min >= $max) {
            __ts_validation_exception($this->component, 'The [min] value must be less than the [max] value.');
        }

        if ($step <= 0) {
            __ts_validation_exception($this->component, 'The [step] value must be greater than zero.');
        }

        if ($step > ($max - $min)) {
            __ts_validation_exception($this->component, 'The [step] value must not be greater than the distance between [min] and [max].');
        }
    }

    private function initial(mixed $value, mixed $min, mixed $max): array
    {
        if (blank($value)) {
            return [$min, $max];
        }

        $label = $this->wireable() ? 'wire:model' : 'value';

        if (! is_array($value) || count($value) !== 2) {
            __ts_validation_exception($this->component, "The [$label] property must be an array with exactly two values.");
        }

        [$start, $end] = array_values($value);

        if (! is_numeric($start) || ! is_numeric($end)) {
            __ts_validation_exception($this->component, "The [$label] property must contain only numeric values.");
        }

        if ($start > $end) {
            __ts_validation_exception($this->component, "The first value of the [$label] property must be less than or equal to the second one.");
        }

        if ($start < $min || $end > $max) {
            __ts_validation_exception($this->component, "The [$label] property must contain values between [min] and [max].");
        }

        return [$start, $end];
    }
}
