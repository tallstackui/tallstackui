<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class DialRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $horizontal = $this->data('horizontal');
        $position = $this->data('position');

        return [
            'anchor' => match (true) {
                $horizontal && str_contains($position, 'right') => 'left',
                $horizontal && str_contains($position, 'left') => 'right',
                str_contains($position, 'bottom') => 'top',
                default => 'bottom',
            },
        ];
    }
}
