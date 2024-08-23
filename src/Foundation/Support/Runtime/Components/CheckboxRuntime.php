<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class CheckboxRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $bind = $this->bind();

        /** @var string|null|ComponentSlot $label $label */
        $label = $this->data['label'];
        $slot = $label instanceof ComponentSlot;

        return [
            'property' => $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'entangle' => $bind->get('entangle'),
            'position' => $slot && $label->attributes->has('left') ? 'left' : $this->data['position'],
            'alignment' => $slot && $label->attributes->has('start') ? 'start' : 'middle',
            'label' => $label,
        ];
    }
}
