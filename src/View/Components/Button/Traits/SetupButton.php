<?php

namespace TallStackUi\View\Components\Button\Traits;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\View\Components\Button\Circle;

trait SetupButton
{
    protected function manipulation(array $classes): array
    {
        return (match (true) {
            $this->flat => function () use ($classes) {
                // We need to remove the border for the flat style.
                $classes['wrapper.class'] = str_replace('border', '', $classes['wrapper.class']);

                return $classes;
            },
            default => fn () => $classes,
        })();
    }

    protected function setup(): void
    {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : ($this->flat ? 'flat' : 'solid'));
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
