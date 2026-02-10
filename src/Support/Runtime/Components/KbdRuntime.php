<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class KbdRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'tag' => filled($this->data('href')) ? 'a' : 'kbd',
        ];
    }
}
