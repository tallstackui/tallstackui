<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class TooltipRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['sentence' => $this->data('text') ?? $this->data('slot')->toHtml()];
    }
}
