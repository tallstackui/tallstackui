<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Form\Upload\Component;
use TallStackUi\Support\Miscellaneous\UploadEditorOptions;
use TallStackUi\Support\Runtime\AbstractRuntime;

class UploadRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        /** @var Component $component */
        $component = $this->component;

        $bind = $this->bind();

        $data = [
            ...$this->locks(),
            'id' => $bind->get('id'),
            'property' => $property = $bind->get('property'),
            // We can get this directly - without need to check if we're in Livewire
            // context because this component is only used in Livewire context.
            'value' => $value = $this->property($property),
            'invalid' => [
                'status' => $this->errors->has(is_array($value) ? $property.'.*' : $property),
                'quantity' => count($this->errors->get(is_array($value) ? $property.'.*' : $property)),
            ],
            'editing' => (new UploadEditorOptions($component, $component->editor, $component->aspect, __ts_get_component_configuration(Component::class) ?? [], (string) $bind->get('id'), $this->livewire))(),
        ];

        if (is_null($property)) {
            __ts_validation_exception($this->component, 'The component requires a property to bind using [wire:model].');
        }

        return $data;
    }
}
