<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class InputRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'ref' => $property ?? uniqid(),
        ];
    }
}
