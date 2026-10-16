<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use Livewire\Livewire;
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
            // A Livewire update runs on its own route, so Livewire rebuilds
            // the page URL from the snapshot sent with the request.
            'shouldRender' => ! $href || rtrim(Livewire::originalUrl(), '/') === rtrim($href, '/'),
        ];
    }
}
