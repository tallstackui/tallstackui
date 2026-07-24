<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class StatsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $hasHref = filled($this->data('href'));
        $hasClick = $this->data['attributes']->hasAny(['wire:click', 'wire:click.prevent', 'x-on:click']);
        $number = $this->data('number');

        return [
            'tag' => $hasHref ? 'a' : 'div',
            'clickable' => $hasHref || $hasClick,
            'animate' => (bool) $this->data('animated') && is_numeric($number),
            'duration' => max(0, (int) ($this->data('duration') ?? 1)),
        ];
    }
}
