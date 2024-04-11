<?php

namespace TallStackUi\View\Components\Button\Traits;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\View\Components\Button\Circle;

trait SetupButton
{
    private function setup(): void
    {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        if (! $this instanceof Circle) {
            return;
        }

        $this->removable = (new ComponentAttributeBag(['wire:target' => "{$this->loading}"]))
            ->when(
                $this->loading || $this->delay,
                fn (ComponentAttributeBag $bag) => $bag->merge([
                    sprintf($this->delay ? 'wire:loading.remove.delay.%s' : 'wire:loading.remove.delay', $this->delay) => '',
                ])
            );
    }
}
