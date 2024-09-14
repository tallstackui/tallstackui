<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TabItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var ComponentSlot|string|null $right */
        $right = $this->data('right');

        /** @var ComponentSlot|string|null $left */
        $left = $this->data('left');

        return [
            'content' => [
                'right' => is_string($right) ? $right : ($right?->toHtml() ?? null),
                'left' => is_string($left) ? $left : ($left?->toHtml() ?? null),
            ],
        ];
    }
}
