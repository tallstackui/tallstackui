<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class ColorRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            ...$this->bind()->only('property', 'id', 'entangle'),
            'select' => $this->data('selectable', false) === true ? [
                'x-on:click' => 'show = !show',
                'class' => 'cursor-pointer caret-transparent',
                'x-on:keydown' => '$event.preventDefault()',
                'spellcheck' => 'false',
            ] : [],
        ];
    }
}
