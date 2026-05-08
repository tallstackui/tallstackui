<?php

namespace TallStackUi\Support\Runtime;

use Error;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\WireDirective;
use TallStackUi\Support\Blade\BindProperty;
use TallStackUi\TallStackUiComponent;

use function Livewire\invade;

abstract class AbstractRuntime
{
    public function __construct(
        protected TallStackUiComponent $component,
        protected array $data,
        protected readonly Factory $factory,
        protected readonly ?Component $livewire = null,
        protected readonly ?ViewErrorBag $errors = null,
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
            'invalidate' => $this->data['invalidate'] ?? config('ts-ui.invalidate_global') ?? false,
            // Livewire here is a boolean to check if the
            // component is being used within a Livewire context.
            'livewire' => $this->livewire !== null,
        ])->toCollection();
    }

    /**
     * Compiles the `wire:change` event for the component when we are in the Livewire context.
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
     * Get the correct value to use in the validation step.
     * The value of a Livewire component is `$property` - when in
     * the context of Livewire, or the `$value` provided.
     */
    protected function property(?string $property): mixed
    {
        if (is_null($property) || ! property_exists($this->livewire, $property)) {
            return null;
        }

        try {
            return data_get($this->livewire, $property);
        } catch (Error) {
            return null;
        }
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

        $decoded = str_replace('"', '', htmlspecialchars_decode($value));

        // This function aims to sanitize the value, removing the
        // brackets and converting the value to the correct type.
        $sanitize = function (string $value): int|string {
            $value = trim(str_replace(['[', ']'], '', $value));

            return ctype_digit($value) ? (int) $value : $value;
        };

        // If the value is not an array, we just sanitize the value.
        if (! str_contains($decoded, ',')) {
            $result = $sanitize($decoded);
            $array = str_contains($decoded, '[') || str_contains($decoded, ']');

            return $array ? [$result] : $result;
        }

        // If the value is an array, we need to explode
        // the string and map the values to sanitize them.
        return array_map($sanitize, explode(',', $decoded));
    }

    /**
     * Tells whether the current component is rendering inside an ancestor's
     * `<x-slot:left>` or `<x-slot:right>`. Used by select.native, select.styled
     * and input.select to switch into "side" mode.
     *
     * Reads `slotStack` instead of `slots` because Laravel keeps closed slots
     * in `slots` (the key stays, the value just becomes a ComponentSlot), so
     * a closed slot on an ancestor would leak into a sibling that rendered
     * after it (issue #1276). `slotStack` only holds slots whose body is
     * currently being captured.
     */
    protected function slots(): array
    {
        $slotStack = invade($this->factory)->slotStack ?? [];

        $left = false;
        $right = false;

        foreach ($slotStack as $frame) {
            foreach ($frame as $entry) {
                $name = $entry[0] ?? null;

                if ($name === 'left') {
                    $left = true;
                } elseif ($name === 'right') {
                    $right = true;
                }
            }
        }

        return [$left, $right];
    }

    protected function value(?string $property = null, mixed $value = null): mixed
    {
        return $this->wireable() && ! is_null($property) && property_exists($this->livewire, $property)
            ? $this->property($property)
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
