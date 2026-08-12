<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class LoadingRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'name' => $this->livewire?->getName(),
        ];
    }
}
