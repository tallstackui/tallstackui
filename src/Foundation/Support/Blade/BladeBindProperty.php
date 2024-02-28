<?php

namespace TallStackUi\Foundation\Support\Blade;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BladeBindProperty
{
    public function __construct(
        private readonly ComponentAttributeBag $attributes,
        private readonly ?ViewErrorBag $errors = null,
        private readonly bool $invalidate = false,
        private readonly bool $livewire = false,
        private ?BladeSupport $support = null,
    ) {
        $this->support = new BladeSupport($this->attributes, $this->livewire);
    }

    public function data(): array
    {
        return [
            $property = $this->bind(),
            $this->error($property),
            $this->id($property),
            $this->support->entangle(),
        ];
    }

    private function bind(): ?string
    {
        return $this->livewire && $this->support->wire() instanceof WireDirective
            ? $this->support->wire()->value()
            // For components such as datepicker, timepicker and others, we use an
            // "alternative" (attribute) way of defining the parameter that will be
            // used to obtain validation errors without being "name", because as these
            // components can be used out of Livewire, "name" is used no input hidden.
            : $this->attributes->get('name', $this->attributes->get('alternative'));
    }

    private function error(?string $property = null): bool
    {
        if (! $this->errors instanceof ViewErrorBag) {
            return false;
        }

        if ($this->errors->isEmpty() || $this->invalidate) {
            return false;
        }

        return $property && $this->errors->has($property);
    }

    private function id(?string $property = null): ?string
    {
        return $this->attributes->get('id', $property);
    }
}
