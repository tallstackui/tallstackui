<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class InputSelectRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $bind = $this->bind();

        $left = $this->data('left');
        $right = $this->data('right');

        $hasLeft = $left instanceof ComponentSlot && ! $left->isEmpty();
        $hasRight = $right instanceof ComponentSlot && ! $right->isEmpty();
        $hasSlot = $hasLeft || $hasRight;

        return [
            'property' => $property = $bind->get('property'),
            'error' => $bind->get('error'),
            'id' => $bind->get('id'),
            'ref' => $property ?? uniqid(),
            'hasLeft' => $hasLeft,
            'hasRight' => $hasRight,
            'hasSlot' => $hasSlot,
        ];
    }
}
