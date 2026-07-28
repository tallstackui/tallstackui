<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class ErrorsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'align' => $this->alignment(),
            'bag' => $this->alignable(),
        ];
    }
}
