<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class SelectNativeRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [...$this->bind()->only('property', 'error')];
    }
}
