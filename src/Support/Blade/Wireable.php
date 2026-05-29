<?php

namespace TallStackUi\Support\Blade;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class Wireable
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

    /**
     * Whether the wire:model carries a debounce or throttle modifier.
     *
     * @throws Exception
     */
    public function debounced(): bool
    {
        if (! ($wire = $this->wire()) instanceof WireDirective) {
            return false;
        }

        return $wire->hasModifier('debounce') || $wire->hasModifier('throttle');
    }

    /**
     * Get the entangle directive.
     *
     * When $defer is true we always return a deferred entangle (without the
     * `.live` chain), so the value is not pushed to the server immediately.
     * This is used by the number component to let Livewire's own wire:model
     * debounce/throttle drive the network sync of the control buttons.
     *
     * @throws Exception
     */
    public function entangle(bool $defer = false): string
    {
        if (! ($wire = $this->wire()) instanceof WireDirective) {
            return Blade::render('null');
        }

        $property = $wire->value();

        if ($defer) {
            return Blade::render("\$wire.entangle('{$property}')");
        }

        return $wire->hasModifier('live') || $wire->hasModifier('blur')
            ? Blade::render("\$wire.entangle('{$property}').live")
            : Blade::render("\$wire.entangle('{$property}')");
    }

    // This is a helper function commonly used in Blade
    // files to allow data to be passed between Blade and JS.
    public function json(array $data = []): string
    {
        return "JSON.parse(atob('".base64_encode(json_encode($data))."'))";
    }

    /** @throws Exception */
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

        return ! $wire->directive() && ! $wire->value() ? null : $wire;
    }
}
