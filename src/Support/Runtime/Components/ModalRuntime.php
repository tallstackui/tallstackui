<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class ModalRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'event' => $event = str($this->data('id'))->slug()->kebab(),
            'open' => $event.'-open',
            'close' => $event.'-close',
        ];
    }
}
