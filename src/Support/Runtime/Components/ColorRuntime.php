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

        $locks = $this->locks();

        return [
            ...$this->bind()->only('property', 'id', 'entangle'),
            ...$locks,
            // Read off the component, not $this->data(): the snapshot predates
            // the config defaults CompileConfigurations writes onto the props.
            'select' => $component->selectable === true && ! $locks['locked'] ? [
                'x-on:click' => 'show = !show',
                'class' => 'cursor-pointer caret-transparent',
                'x-on:keydown' => '$event.preventDefault()',
                'spellcheck' => 'false',
            ] : [],
        ];
    }
}
