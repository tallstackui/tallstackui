<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class TabItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var ComponentSlot|string|null $right */
        $right = $this->data('right');

        /** @var ComponentSlot|string|null $left */
        $left = $this->data('left');

        /** @var string|null $href */
        $href = $this->data('href');

        return [
            'content' => [
                'right' => is_string($right) ? $right : ($right?->toHtml() ?? null),
                'left' => is_string($left) ? $left : ($left?->toHtml() ?? null),
            ],
            'shouldRender' => ! $href || rtrim(request()->url(), '/') === rtrim($href, '/'),
        ];
    }
}
