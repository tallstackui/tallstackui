<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Form\Color\Component as Color;
use TallStackUi\Support\Runtime\AbstractRuntime;

class ColorRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        /** @var Color $component */
        $component = $this->component;

        return [
            ...$this->bind()->only('property', 'id', 'entangle'),
            // Read off the component, not $this->data(): the snapshot predates
            // the config defaults CompileConfigurations writes onto the props.
            'select' => $component->selectable === true ? [
                'x-on:click' => 'show = !show',
                'class' => 'cursor-pointer caret-transparent',
                'x-on:keydown' => '$event.preventDefault()',
                'spellcheck' => 'false',
            ] : [],
        ];
    }
}
