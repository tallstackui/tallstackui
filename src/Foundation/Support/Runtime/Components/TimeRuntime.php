<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use InvalidArgumentException;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TimeRuntime extends AbstractRuntime
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
        if (! is_string($value)) {
            throw new InvalidArgumentException('The time [value] must be a string.');
        }
    }
}
