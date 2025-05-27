<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class KeyValueRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $property = $bind->get('property');
        $value = $this->value($property);

        $this->validate($value);

        return [...$bind->only('entangle')];
    }

    private function validate(mixed $value): void
    {
        if (! is_array($value)) {
            __ts_validation_exception($this->component, 'The [value] must be an array.');
        }

        if (blank($value)) {
            return;
        }

        $valid = collect($value)
            ->lazy()
            ->every(fn (array $item) => array_key_exists('key', $item) && array_key_exists('value', $item));

        if (! $valid) {
            __ts_validation_exception($this->component, 'The [value] must be an array of arrays with [key] and [value] keys.');
        }
    }
}
