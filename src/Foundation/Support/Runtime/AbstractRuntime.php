<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\WireDirective;
use TallStackUi\Foundation\Support\Blade\BindProperty;
use TallStackUi\TallStackUiComponent;

abstract class AbstractRuntime
{
    public function __construct(
        protected TallStackUiComponent $component,
        protected array $data,
        protected readonly ?Component $livewire = null,
        protected readonly ?ViewErrorBag $errors = null
    ) {
        //
    }

    /**
     * Determine the runtime properties for the component.
     */
    abstract public function runtime(): array;

    /**
     * Shortcut to retrieve the bind data ready to use as a collection.
     *
     * @throws Exception
     */
    protected function bind(): Collection
    {
        return app(BindProperty::class, [
            'attributes' => $this->data['attributes'],
            'errors' => $this->errors,
            'invalidate' => $this->data['invalidate'] ?? config('tallstackui.invalidate_global') ?? false,
            // Livewire here is a boolean to check if the
            // component is being used within a Livewire context.
            'livewire' => $this->livewire !== null,
        ])->toCollection();
    }

    /**
     * Compiles the `wire:change` event for the component when we are in Livewire context.
     */
    protected function change(): ?array
    {
        if (! $this->wireable()) {
            return null;
        }

        /** @var ComponentAttributeBag $attributes */
        $attributes = $this->data['attributes'];

        /** @var WireDirective|null $wire */
        $wire = $attributes->wire('change');

        if (! $wire || ($method = $wire->value()) === false) {
            return null;
        }

        return ['id' => $this->livewire->getId(), 'method' => $method];
    }

    /**
     * Get data from $this->data using data_get when $key is set or return the whole data as a collection.
     *
     * @return mixed|Collection
     */
    protected function data(?string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return data_get($this->data, $key, $default);
        }

        return collect($this->data);
    }

    /**
     * Sanitizes the value to prepare the component when we are
     * out of the Livewire context, applied to components: `date`,
     * `select.styled`, `tag` and `time`.
     */
    protected function sanitize(): null|int|string|array
    {
        $value = $this->data['attributes']?->get('value');
        $value = $value === 'null' ? null : ($value === '[]' ? [] : $value);

        // We just transform the value when is not a Livewire
        // component or when the value is not empty and is a string.
        if ($this->wireable() || (! $value || ! is_string($value))) {
            return $value;
        }

        $string = str(htmlspecialchars_decode($value))->remove('"');

        // This function aims to sanitize the value, removing the
        // brackets and converting the value to the correct type.
        // We avoid use the `Stringable` here to increase the performance.
        $sanitize = function (string $value): int|string {
            $value = trim(str_replace(['[', ']'], '', $value));

            return ctype_digit($value) ? (int) $value : $value;
        };

        // If the value is not an array, we just sanitize the value.
        if (! $string->contains(',')) {
            $result = $sanitize($string->remove(['[', ']'])->trim()->value());

            return $string->contains(['[', ']']) ? [$result] : $result;
        }

        // If the value is an array, we need to explode
        // the string and map the values to sanitize them.
        return $string->explode(',')
            ->collect()
            ->map(fn (string|int $value) => $sanitize($value))
            ->toArray();
    }

    /**
     * Get the correct value to use in the validation step.
     * The value of a Livewire component `$property` - when in
     * the context of Livewire, or the `$value` provided.
     */
    protected function value(?string $property = null, mixed $value = null): mixed
    {
        return $this->wireable() && ! is_null($property) && property_exists($this->livewire, $property)
            ? data_get($this->livewire, $property)
            : ($value ?: $this->data['attributes']->get('value'));
    }

    /**
     * Determines whether we are within the context of Livewire.
     */
    protected function wireable(): bool
    {
        return $this->livewire !== null;
    }
}
