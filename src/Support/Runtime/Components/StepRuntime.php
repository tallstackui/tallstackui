<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class StepRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['lines' => $this->skeleton(3)];
    }
}
