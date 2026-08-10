<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class AvatarRuntime extends AbstractRuntime
{
    // Naming these [size] and [source] silently breaks: the component data is
    // captured before validation resolves the size, and a public method is
    // already exposed under its own name as an invokable variable.
    public function runtime(): array
    {
        return [
            'scale' => $this->component->size, // @phpstan-ignore-line
            'src' => $this->component->source(), // @phpstan-ignore-line
        ];
    }
}
