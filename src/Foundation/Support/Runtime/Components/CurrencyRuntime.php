<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class CurrencyRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        return [
            ...$this->bind(),
            'value' => $this->sanitize(),
        ];
    }
}
