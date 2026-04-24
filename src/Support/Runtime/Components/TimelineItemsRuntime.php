<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class TimelineItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var ComponentSlot|string|null $marker */
        $marker = $this->data('marker');

        return [
            'content' => [
                'marker' => is_string($marker) ? $marker : ($marker?->toHtml() ?? null),
            ],
        ];
    }
}
