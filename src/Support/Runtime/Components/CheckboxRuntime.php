<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class CheckboxRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        /** @var string|null|ComponentSlot $label $label */
        $label = $this->data('label');

        $bind = $this->bind();

        return [
            ...$bind,
            ...$this->locks(),
            'id' => $this->identifier($bind->get('property')),
            'label' => $label,
        ];
    }

    /**
     * Every option of a group carries the same property, so falling back to it
     * alone gives them all the same id. The label's [for] then resolves to the
     * first input on the page and clicking any label picks the first option.
     */
    private function identifier(?string $property): ?string
    {
        $attributes = $this->data('attributes');

        $id = $attributes->get('id');

        if ($id !== null || $property === null) {
            return $id;
        }

        $value = $attributes->get('value');

        return is_scalar($value) && filled($value)
            ? $property.'-'.str($value)->slug()
            : $property;
    }
}
