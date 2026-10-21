<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Form\Number\Component as Number;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Blade\Wireable;
use TallStackUi\Support\Runtime\AbstractRuntime;

class NumberRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        /** @var Number $component */
        $component = $this->component;

        $bind = $this->bind();

        // Read off the component, not $this->data(): the snapshot predates
        // the config defaults CompileConfigurations writes onto the props.
        $chevron = $component->chevron;

        [$left, $right] = [
            $chevron ? 'chevron-down' : 'minus',
            $chevron ? 'chevron-up' : 'plus',
        ];

        $support = new Wireable($this->data['attributes'], $this->wireable());
        $debounce = $support->debounced();

        $data = [
            ...$this->locks(),
            'property' => $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            // When the wire:model is debounced/throttled we defer the entangle
            // so the control buttons rely on Livewire's native debounce instead
            // of pushing the value to the server on every single click.
            'entangle' => $debounce ? $support->entangle(defer: true) : $bind->get('entangle'),
            'debounce' => $debounce,
            'icons' => [
                'left' => TallStackUi::icon($left),
                'right' => TallStackUi::icon($right),
            ],
        ];

        $this->validate();

        return $data;
    }

    // The value is not checked: it is user data, and throwing on it
    // turns a value out of the range into a 500.
    private function validate(): void
    {
        $min = $this->data('min');
        $max = $this->data('max');

        if ($min !== null && $max !== null && $min > $max) {
            __ts_validation_exception($this->component, 'The [min] value must be less than the [max] value.');
        }
    }
}
