<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use Illuminate\Support\Carbon;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class DateRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $data = [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'value' => $value = $this->sanitize(),
            'change' => $this->change(),
        ];

        $value = $this->value($property, $value);

        if (filled($value)) {
            $this->validate($value);
        }

        return $data;
    }

    private function validate(array|string|null $value): void
    {
        $range = $this->data('range');
        $multiple = $this->data('multiple');

        if (is_null($value)) {
            return;
        }

        if (($range || $multiple) && ! is_array($value)) {
            __ts_validation_exception($this->component, 'The [value] must be an array when using the [range] or [multiple].');
        }

        if ($range && count($value) === 2) {
            [$start, $end] = array_map(function (?string $date) {
                return $date !== null ? Carbon::parse($date) : null;
            }, $value);

            if ($start instanceof Carbon && $end instanceof Carbon) {
                if ($start->greaterThan($end)) {
                    __ts_validation_exception($this->component, 'The start date in the [range] must be greater than the second date.');
                }
            }
        }
    }
}
