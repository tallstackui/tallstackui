<?php

namespace TallStackUi\Foundation\Traits;

use Exception;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\WireDirective;
use TallStackUi\View\Components\Form\Pin;
use TallStackUi\View\Components\Select\Styled;

trait LivewireChangeEvent
{
    // As the select.styled component is not an input, the only way to adapt the
    // wire:change event was to create this structure, where we share information
    // about the current livewire component and the method to be triggered in wire:change,
    // so that this is consumed by the javascript associated with the component.
    /** @throws Exception */
    public function change(ComponentAttributeBag $attributes, ?Component $component = null, bool $livewire = true): ?array
    {
        if (! $livewire || ! $component) {
            return null;
        }

        /** @var WireDirective|null $wire */
        $wire = $attributes->wire('change');

        if (! $wire || ! ($method = $wire->value())) {
            return null;
        }

        $class = [
            Styled::class => 'select.styled',
            Pin::class => 'form.pin',
        ][static::class];

        if (! method_exists($component, $method)) {
            throw new Exception("The [wire:change] method [$method] used in [$class] does not exists in the component [{$component->getName()}].");
        }

        return [
            'id' => $component->getId(),
            'method' => $method,
        ];
    }
}
