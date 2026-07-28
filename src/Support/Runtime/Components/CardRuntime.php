<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class CardRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'align' => $this->alignment(),
            'bag' => $this->alignable(),
        ];
    }
}
