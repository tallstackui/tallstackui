<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class SwitcherRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        return [
            ...$this->locks(),
            'checked' => $this->wireable() && (bool) $this->property($this->bind()->get('property')),
        ];
    }
}
