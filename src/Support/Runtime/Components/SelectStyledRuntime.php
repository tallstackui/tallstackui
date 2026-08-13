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

        $side = $left ? 'left' : ($right ? 'right' : null);

        return [
            ...$this->locks(inherit: $side !== null),
            'property' => $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'validate' => $bind->get('validate'),
            'value' => $this->sanitize(),
            'change' => $this->change(),
            'open' => $id ? str($id)->slug()->kebab().'-open' : null,
            'close' => $id ? str($id)->slug()->kebab().'-close' : null,
            'side' => $side,
            'floating' => $side ? $this->factory->getConsumableComponentData('floating') : null,
        ];
    }
}
