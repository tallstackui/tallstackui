<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class InputRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $prefix = $this->data('prefix');
        $suffix = $this->data('suffix');

        $prefixed = $prefix instanceof ComponentSlot && $prefix->attributes->has('button');
        $suffixed = $suffix instanceof ComponentSlot && $suffix->attributes->has('button');

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'validate' => $bind->get('validate'),
            'ref' => $property ?? uniqid(),
            'prefixed' => $prefixed,
            'suffixed' => $suffixed,
            'addon' => $prefixed || $suffixed,
        ];
    }
}
