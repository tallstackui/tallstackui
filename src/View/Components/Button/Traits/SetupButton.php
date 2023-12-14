<?php

namespace TallStackUi\View\Components\Button\Traits;

trait SetupButton
{
    private function setup(): void
    {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }
}
