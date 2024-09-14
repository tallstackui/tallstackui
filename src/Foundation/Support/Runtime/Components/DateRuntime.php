<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
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

        $value = $this->value($value, $property);

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
            [$start, $end] = array_map(fn (?string $date) => Carbon::parse($date), $value);

            if ($start->greaterThan($end)) {
                throw new InvalidArgumentException('The start date in the [range] must be greater than the second date.');
            }
        }
    }
}
