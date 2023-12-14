<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BladeSupport
{
    public function entangle(ComponentAttributeBag|WireDirective $attributes): string
    {
        if (($wire = $this->wire($attributes)) === null) {
            return Blade::render('null');
        }

        $value = $wire->value();

        return $wire->hasModifier('live') || $wire->hasModifier('blur')
            ? Blade::render("@entangle('{$value}').live")
            : Blade::render("@entangle('{$value}')");
    }

    public function json(array $data = []): string
    {
        return "JSON.parse(atob('".base64_encode(json_encode($data))."'))";
    }

    public function wire(ComponentAttributeBag $attributes): ?WireDirective
    {
        // For some unknown reason the macros are not triggered when we
        // are testing with the Feature tests. I assume this happens
        // because Laravel doesn't bootstrap something necessary.
        if (app()->runningUnitTests() || ! $attributes::hasMacro('wire')) {
            return null;
        }

        /** @var WireDirective $wire */
        $wire = $attributes->wire('model');

        if (! $wire->directive() && ! $wire->value()) {
            return null;
        }

        return $wire;
    }
}
