<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class InputSelectorRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $this->validate();

        return [...$this->bind()->only('property', 'error', 'id')];
    }

    private function validate(): void
    {
        $left = $this->data('left');
        $right = $this->data('right');

        if (blank($left) && blank($right)) {
            __ts_validation_exception($this->component, 'You must provide a [left] or [right] slot with a select component.');
        }

        if ($left && $right) {
            __ts_validation_exception($this->component, 'You cannot use [left] and [right] at the same time.');
        }
    }
}
