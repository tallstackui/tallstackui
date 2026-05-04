<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class SelectStyledRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $id = $this->data('id');

        [$left, $right] = $this->slots();

        return [
            'property' => $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'validate' => $bind->get('validate'),
            'value' => $this->sanitize(),
            'change' => $this->change(),
            'disabled' => (bool) $this->data['attributes']->get('disabled', $this->data['attributes']->get('readonly', false)),
            'open' => $id ? str($id)->slug()->kebab().'-open' : null,
            'close' => $id ? str($id)->slug()->kebab().'-close' : null,
            'side' => $left ? 'left' : ($right ? 'right' : null),
        ];
    }
}
