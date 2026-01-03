<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class RangeRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        return [...$this->bind()->only('property', 'error', 'id')];
    }
}
