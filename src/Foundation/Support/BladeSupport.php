<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BladeSupport
{
    public function __construct(
        private readonly ComponentAttributeBag $attributes
    ) {
        //
    }

    public function entangle(ComponentAttributeBag $attributes): string
    {
        if (($wire = $this->wireable($attributes)) === null) {
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

    public function wireable(ComponentAttributeBag $attributes): ?WireDirective
    {
        // For some unknown reason the macros are not defined when we are testing.
        // I assume this happens because Laravel doesn't bootstrap something necessary
        // To the macro works when we are testing using the `$this->blade()` method.
        if (! $attributes::hasMacro('wire')) {
            return null;
        }

        /** @var WireDirective $wire */
        $wire = $attributes->wire('model');

        if (! $wire->directive() && ! $wire->value()) {
            return null;
        }

        return $wire;
    }

    public function validation(ComponentAttributeBag $attributes): bool
    {
        return $attributes->get('wire:model') !== null;
    }
}
