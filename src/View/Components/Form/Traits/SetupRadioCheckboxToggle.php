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

    private function setup(): void
    {
        $this->position = match ($this->position) {
            'left' => 'left',
            default => 'right',
        };

        $this->size = match (true) {
            $this->xs !== null => 'xs',
            $this->sm !== null => 'sm',
            $this->lg !== null => 'lg',
            default => 'md',
        };
    }
}
