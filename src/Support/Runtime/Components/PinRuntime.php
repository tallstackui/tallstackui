<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class PinRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $separator = $this->data('separator');
        $separators = $this->component->splits(); // @phpstan-ignore-line

        return [
            ...$this->locks(),
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'validate' => $bind->get('validate'),
            'hash' => $this->wireable() ? $this->livewire->getId().'-'.$property : uniqid(),
            'change' => $this->change(),
            'type' => $this->data('password') ? 'password' : 'text',
            'symbol' => $separator === true ? '-' : $separator,
            'separators' => $separators,
            // The boxes opening and closing each chunk, so a grouped
            // pin only rounds the outer corners of every chunk.
            'firsts' => [1, ...array_map(fn (int $position): int => $position + 1, $separators)],
            'lasts' => [...$separators, $this->data('length')],
        ];
    }
}
