<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class TooltipRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['sentence' => $this->data('text') ?? $this->data('slot')->toHtml()];
    }
}
