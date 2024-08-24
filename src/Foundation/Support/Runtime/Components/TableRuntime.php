<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TableRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [...$this->bind()->only('entangle')];
    }
}
