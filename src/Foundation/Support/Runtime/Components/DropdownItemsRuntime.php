<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class DropdownItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['tag' => filled($this->data('href')) ? 'a' : ($this->data('text') ? 'button' : 'div')];
    }
}
