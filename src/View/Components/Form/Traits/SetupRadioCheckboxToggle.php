<?php

namespace TallStackUi\View\Components\Form\Traits;

use Illuminate\View\ComponentSlot;

trait SetupRadioCheckboxToggle
{
    public function sloteable(string|null|ComponentSlot $label): array
    {
        $sloteable = $label instanceof ComponentSlot;

        $position = $sloteable && $label->attributes->has('left') ? 'left' : $this->position;
        $alignment = $sloteable && $label->attributes->has('start') ? 'start' : 'middle';

        return [
            $position,
            $alignment,
            $label,
        ];
    }

    private function shareable(): void
    {
        $this->id ??= uniqid();

        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }
}
