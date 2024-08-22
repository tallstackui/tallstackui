<?php

namespace TallStackUi\Foundation\Support\Blade;

use Exception;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

class BindProperty
{
    public function __construct(
        private readonly ComponentAttributeBag $attributes,
        private readonly ?ViewErrorBag $errors = null,
        private readonly bool $invalidate = false,
        private readonly bool $livewire = false,
        private ?Wireable $support = null,
    ) {
        $this->support = new Wireable($this->attributes, $this->livewire);
    }

    /**
     * Compiles an array of needed information to bind a property.
     *
     * @throws Exception
     */
    public function data(): array
    {
        return [
            $property = $this->bind(),
            $this->error($property),
            $this->id($property),
            $this->support->entangle(),
        ];
    }

    /**
     * Get the element bind.
     *
     * @throws Exception
     */
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

    /**
     * Check if the property has an error.
     */
    private function error(?string $property = null): bool
    {
        if (! $this->errors instanceof ViewErrorBag) {
            return false;
        }

        // Invalidate negates the need to check for errors.
        if ($this->errors->isEmpty() || $this->invalidate) {
            return false;
        }

        return $property && $this->errors->has($property);
    }

    /**
     * Get the element ID.
     */
    private function id(?string $property = null): ?string
    {
        return $this->attributes->get('id', $property);
    }
}
