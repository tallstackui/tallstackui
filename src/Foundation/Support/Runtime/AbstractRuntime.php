<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Blade\BindProperty;

abstract class AbstractRuntime
{
    public function __construct(
        protected array $data,
        protected readonly object $component,
        protected readonly ?Component $livewire = null,
        protected readonly ?ViewErrorBag $errors = null
    ) {
        //
    }

    /**
     * Shortcut to retrieve the bind data ready to use as a collection.
     *
     * @throws Exception
     */
    public function bind(): Collection
    {
        return app(BindProperty::class, [
            'attributes' => $this->data['attributes'],
            'errors' => $this->errors,
            'invalidate' => $this->data['invalidate'] ?? false,
            // Livewire here is a boolean to check if the
            // component is being used within a Livewire context.
            'livewire' => $this->livewire !== null,
        ])->toCollection();
    }

    /**
     * Compiles the `wire:change` event for the component when we are in Livewire context.
     */
    public function change(): ?array
    {
        if (! $this->wireable()) {
            return null;
        }

        /** @var ComponentAttributeBag $attributes */
        $attributes = $this->data['attributes'];

        /** @var WireDirective|null $wire */
        $wire = $attributes->wire('change');

        if (! $wire || ! ($method = $wire->value()) !== false) {
            return null;
        }

        return ['id' => $this->livewire->getId(), 'method' => $method];
    }

    /**
     * Determine the runtime properties for the component.
     */
    abstract public function runtime(): array;

    /**
     * Get the correct value to use in the validation step.
     * The value of a Livewire component `$property` - when in
     * the context of Livewire, or the `$value` provided.
     */
    public function value(mixed $value, ?string $property = null): mixed
    {
        return $this->wireable() && ! is_null($property) && property_exists($this->livewire, $property)
            ? data_get($this->livewire, $property)
            : $value;
    }

    /**
     * Determines whether we are within the context of Livewire.
     */
    public function wireable(): bool
    {
        return $this->livewire !== null;
    }

    /**
     * Get data from $this->data using data_get when $key is set or return the whole data as a collection.
     */
    protected function data(?string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return data_get($this->data, $key, $default);
        }

        return collect($this->data);
    }
}
