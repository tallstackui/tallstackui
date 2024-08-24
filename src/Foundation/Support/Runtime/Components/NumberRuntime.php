<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class NumberRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $chevron = $this->data['chevron'];

        [$left, $right] = [
            $chevron ? 'chevron-down' : 'minus',
            $chevron ? 'chevron-up' : 'plus',
        ];

        return [
            ...$this->bind(),
            'icons' => [
                'left' => TallStackUi::icon($left),
                'right' => TallStackUi::icon($right),
            ],
        ];
    }
}
