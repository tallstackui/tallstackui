<?php

namespace TallStackUi\View\Components\Form\Traits;

trait Setup
{
    protected function setup(): void
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
