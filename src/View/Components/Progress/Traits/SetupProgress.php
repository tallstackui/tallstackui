<?php

namespace TallStackUi\View\Components\Progress\Traits;

trait SetupProgress
{
    public function setup(): void
    {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->style = $this->light ? 'light' : 'solid';
    }
}
