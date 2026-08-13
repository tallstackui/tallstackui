<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Support\Runtime\AbstractRuntime;

class SelectNativeRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        [$left, $right] = $this->slots();

        $side = $left ? 'left' : ($right ? 'right' : null);

        return [
            ...$this->bind()->only('property', 'error', 'validate'),
            ...$this->locks(inherit: $side !== null),
            'side' => $side,
        ];
    }
}
