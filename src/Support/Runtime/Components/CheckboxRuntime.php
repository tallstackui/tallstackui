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
            'label' => $label,
        ];
    }
}
