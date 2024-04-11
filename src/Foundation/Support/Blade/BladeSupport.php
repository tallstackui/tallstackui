<?php

namespace TallStackUi\Foundation\Support\Blade;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BladeSupport
{
    public function __construct(
        private readonly ?ComponentAttributeBag $attributes = null,
        // The idea behind using the $livewire boolean here is to ensure
        // that the component is being used within the context of a Livewire
        // component, where the $__livewire variable exists, so we guarantee
        // the correct application of the entangle directive.
        private readonly bool $livewire = false,
    ) {
        //
    }

    public function entangle(): string
    {
        if (! ($wire = $this->wire()) instanceof WireDirective) {
            return Blade::render('null');
        }

        $property = $wire->value();

        return $wire->hasModifier('live') || $wire->hasModifier('blur')
            ? Blade::render("@entangle('{$property}').live")
            : Blade::render("@entangle('{$property}')");
    }

    public function json(array $data = []): string
    {
        return "JSON.parse(atob('".base64_encode(json_encode($data))."'))";
    }

    public function wire(): ?WireDirective
    {
        if (! $this->attributes instanceof ComponentAttributeBag) {
            throw new Exception('The attributes was not defined.');
        }

        // For some unknown reason the macros are not defined when we are testing.
        // I assume this happens because Laravel doesn't bootstrap something necessary
        // To the macro works when we are testing using the `$this->blade()` method.
        if (! $this->livewire || ! $this->attributes::hasMacro('wire')) {
            return null;
        }

        $wire = $this->attributes->wire('model');

        if (! $wire->directive() && ! $wire->value()) {
            return null;
        }

        return $wire;
    }
}
