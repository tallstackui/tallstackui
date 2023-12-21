<?php

namespace TallStackUi\Foundation\Support;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\Factory;
use Livewire\Component;
use TallStackUi\Facades\TallStackUi;

class BindProperty
{
    public function __construct(
        private readonly ComponentAttributeBag $attributes,
        private readonly ViewErrorBag $errors,
        private readonly Factory $factory,
        private readonly bool $livewire = false,
    ) {
        //
    }

    public function data(): array
    {
        return [
            $this->bind(),
            $this->error(),
            $this->id(),
        ];
    }

    private function bind(): ?string
    {
        $wire = TallStackUi::blade($this->attributes)->wireable($this->livewire);

        // We prioritize the Livewire context.
        return $this->livewire
            ? $wire->value()
            : $this->attributes->get('name');
    }

    private function error(): bool
    {
        // Using getConsumableComponentData we check if the `invalidate`
        // was set to don't display validation errors.
        if ($this->errors->isEmpty() || $this->factory->getConsumableComponentData('invalidate', false) === true) {
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
