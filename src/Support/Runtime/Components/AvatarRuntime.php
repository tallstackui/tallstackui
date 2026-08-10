<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class AvatarRuntime extends AbstractRuntime
{
    // Naming this [size] silently breaks: the component data is captured
    // before validation resolves it and gets reapplied on render.
    public function runtime(): array
    {
        return [
            'scale' => $this->component->size, // @phpstan-ignore-line
        ];
    }
}
