<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class ListRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['lines' => $this->skeleton(4)];
    }
}
