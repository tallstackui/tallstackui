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
                if (isset($classes['wrapper.class'])) {
                    $classes['wrapper.class'] = str_replace('border', '', $classes['wrapper.class']);
                } elseif (isset($classes['wrapper.base'])) {
                    $classes['wrapper.base'] = str_replace('border', '', $classes['wrapper.base']);
                }

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

        $this->wireable['icon'] = (new ComponentAttributeBag(['wire:target' => "{$this->loading}"]))
            ->when(
                $this->loading || $this->delay,
                fn (ComponentAttributeBag $bag) => $bag->merge([
                    sprintf($this->delay ? 'wire:loading.remove.delay.%s' : 'wire:loading.remove.delay', $this->delay) => '',
                ])
            )
            ->when($this->loading === '1', fn (ComponentAttributeBag $bag) => $bag->except('wire:target'));

        $this->wireable['text'] = (new ComponentAttributeBag(['wire:target' => "{$this->loading}"]))
            ->when(
                $this->loading || $this->delay,
                fn (ComponentAttributeBag $bag) => $bag->merge([
                    sprintf($this->delay ? 'wire:loading.remove.delay.%s' : 'wire:loading.remove.delay', $this->delay) => '',
                ])
            );
    }
}
