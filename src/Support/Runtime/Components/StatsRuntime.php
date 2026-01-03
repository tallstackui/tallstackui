<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class StatsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            'tag' => filled($this->data('href') || $this->data['attributes']->hasAny(['wire:click', 'wire:click.prevent', 'x-on:click']))
                ? 'a'
                : 'div',
        ];
    }
}
