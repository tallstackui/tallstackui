<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;

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
            $this->bind(),
            $this->error(),
            $this->id(),
            $this->support->entangle(),
        ];
    }

    private function bind(): ?string
    {
        // We prioritize the Livewire context.
        return $this->livewire && $this->support->wire() !== null
            ? $this->support->wire()->value()
            : $this->attributes->get('name');
    }

    private function error(): bool
    {
        if (! $this->errors) {
            return false;
        }

        if ($this->errors->isEmpty() || $this->invalidate) {
            return false;
        }

        // The first step is determine how the component is being used.
        // When $component is set, then we are in Livewire context, otherwise
        // we will to consider it as a normal Blade vanilla context.
        return $this->errors->has($this->bind());
    }

    private function id(): ?string
    {
        return $this->attributes->get('id', $this->bind());
    }
}
