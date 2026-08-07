<?php

namespace TallStackUi\Components\Traits;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Components\Button\Circle\Component as Circle;
use TallStackUi\Components\Button\Normal\Component as Button;
use TallStackUi\Components\Spinner\Component as Spinner;

trait ButtonSetup
{
    protected function guard(): void
    {
        $types = array_values(array_diff(Spinner::TYPES, Spinner::TEXTUAL));

        if (! in_array($this->spinner, $types, true)) {
            __ts_validation_exception($this, 'The [spinner] must be one of: ['.implode(', ', $types).'].');
        }
    }

    protected function manipulation(array $classes): array
    {
        return (match (true) {
            // When the button is a flat style, part of what we need
            // to do to make the flat work is remove the borders.
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
        // Circle intentionally reads the button config key so one setting drives both.
        $this->spinner ??= __ts_get_component_configuration(Button::class, 'spinner') ?? 'gradient';

        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : ($this->flat ? 'flat' : 'solid'));
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        if ($this instanceof Button) {
            $this->position = $this->position === 'right' ? 'right' : 'left';
        }

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
