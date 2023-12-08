<?php

namespace TallStackUi\View\Components\Form\Traits;

trait SetupRadioCheckboxToggle
{
    private function setup(): void
    {
        $this->id ??= uniqid();

        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
        $this->position = $this->position === 'right' ? 'right' : 'left';
    }
}
