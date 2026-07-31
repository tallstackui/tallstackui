<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class TooltipRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'sentence' => $this->data('text') ?? $this->data('slot')->toHtml(),
            // Merged into the attribute bag because a Blade component tag
            // does not compile with a directive between its attributes.
            'settings' => array_filter([
                'data-tooltip-delay' => $this->data('delay'),
                'data-tooltip-color' => $this->data('balloon'),
            ]),
        ];
    }
}
