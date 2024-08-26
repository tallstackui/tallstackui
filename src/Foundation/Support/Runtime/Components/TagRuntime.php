<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TagRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'value' => $this->sanitize(),
        ];
    }
}
