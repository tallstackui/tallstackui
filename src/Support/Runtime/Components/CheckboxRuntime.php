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
        $slot = $label instanceof ComponentSlot;

        return [
            ...$this->bind(),
            // Not named [position]: the component declares a prop with that name, and
            // Laravel applies the prop snapshot after the runtime, so the prop would
            // always win and <x-slot:label left> would never reach the wrapper.
            'labelPosition' => $slot && $label->attributes->has('left') ? 'left' : $this->data('position'),
            'alignment' => $slot && $label->attributes->has('start') ? 'start' : 'middle',
            'label' => $label,
        ];
    }
}
