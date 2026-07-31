<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class IconRuntime extends AbstractRuntime
{
    // Naming these [size] and [color] silently breaks: the component data is
    // captured before validation resolves them and gets reapplied on render.
    public function runtime(): array
    {
        return [
            'scale' => $this->component->size, // @phpstan-ignore-line
            'tone' => $this->component->color, // @phpstan-ignore-line
        ];
    }
}
