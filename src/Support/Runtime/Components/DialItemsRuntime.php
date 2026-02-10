<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class DialItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return ['tag' => filled($this->data('href')) ? 'a' : 'button'];
    }
}
