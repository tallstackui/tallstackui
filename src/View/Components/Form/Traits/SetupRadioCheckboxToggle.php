<?php

namespace TallStackUi\View\Components\Form\Traits;

trait SetupRadioCheckboxToggle
{
    public ?string $id = null;

    public ?string $lg = null;

    public ?string $md = null;

    public ?string $position = 'right';

    public ?string $size = null;

    public ?string $sm = null;

    public ?string $xs = null;

    private function setup(): void
    {
        $this->id ??= uniqid();

        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
        $this->position = $this->position === 'right' ? 'right' : 'left';
    }
}
