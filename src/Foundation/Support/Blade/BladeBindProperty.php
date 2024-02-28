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
            $bind = $this->bind(),
            $this->error($bind),
            $this->id(),
            $this->support->entangle(),
        ];
    }

    private function bind(): ?string
    {
        // We prioritize the Livewire context.
        return $this->livewire && $this->support->wire() instanceof WireDirective
            ? $this->support->wire()->value()
            : $this->attributes->get('name', $this->attributes->get('bypass'));
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

    private function id(): ?string
    {
        return $this->attributes->get('id', $this->bind());
    }
}
