<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BladeSupport
{
    public function entangle(ComponentAttributeBag|WireDirective $attributes): string
    {
        /** @var WireDirective $wire */
        $wire = $attributes instanceof ComponentAttributeBag
            ? $attributes->wire('model')
            : $attributes;

        if (! $wire->directive() && ! $wire->value()) {
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
}
