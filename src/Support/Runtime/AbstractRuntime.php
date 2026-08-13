<?php

namespace TallStackUi\Support\Runtime;

use Error;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use Livewire\Component;
use Livewire\WireDirective;
use TallStackUi\Support\Blade\BindProperty;
use TallStackUi\TallStackUiComponent;

use function Livewire\invade;

abstract class AbstractRuntime
{
    private const ALIGNMENTS = ['start', 'center', 'end', 'between'];

    private const UNWRAPPED = 'unwrapped';

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
     * Attributes of an alignable slot, without the alignment keywords.
     */
    protected function alignable(string $slot = 'footer'): ComponentAttributeBag
    {
        $content = $this->data[$slot] ?? null;

        return $content instanceof ComponentSlot
            ? $content->attributes->except([...self::ALIGNMENTS, self::UNWRAPPED])
            : new ComponentAttributeBag;
    }

    /**
     * Resolves the slot alignment. Returns null
     * when the slot opts out through [unwrapped].
     */
    protected function alignment(string $slot = 'footer', string $default = 'end'): ?string
    {
        $content = $this->data[$slot] ?? null;

        if (! $content instanceof ComponentSlot) {
            return $default;
        }

        $alignments = array_values(array_filter(
            self::ALIGNMENTS,
            fn (string $alignment): bool => (bool) $content->attributes->get($alignment, false)
        ));

        $unwrapped = (bool) $content->attributes->get(self::UNWRAPPED, false);

        if ($unwrapped && $alignments !== []) {
            __ts_validation_exception($this->component, "The [{$slot}] slot cannot use [unwrapped] together with [".implode(', ', $alignments).'].');
        }

        if (count($alignments) > 1) {
            __ts_validation_exception($this->component, "The [{$slot}] slot cannot combine the alignments [".implode(', ', $alignments).'].');
        }

        return $unwrapped ? null : ($alignments[0] ?? $default);
    }

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
     * Both lock the component and paint the same. Only `readonly` keeps
     * submitting the value.
     *
     * @return array{disabled: bool, readonly: bool, locked: bool}
     */
    protected function locks(bool $inherit = false): array
    {
        $disabled = $this->data('disabled') ?? $this->data['attributes']->get('disabled');
        $readonly = $this->data('readonly') ?? $this->data['attributes']->get('readonly');

        // A side component is half of input.select, so the lock belongs to the
        // compound control. Reached last, so its own lock still wins.
        if ($inherit) {
            $disabled ??= $this->factory->getConsumableComponentData('disabled');
            $readonly ??= $this->factory->getConsumableComponentData('readonly');
        }

        $disabled = (bool) $disabled;
        $readonly = (bool) $readonly;

        return [
            'disabled' => $disabled,
            'readonly' => $readonly,
            'locked' => $disabled || $readonly,
        ];
    }

    /**
     * Get the correct value to use in the validation step.
     * The value of a Livewire component is `$property` - when in
     * the context of Livewire, or the `$value` provided.
     */
    protected function property(?string $property): mixed
    {
        // Only the first segment names a real property; data_get() walks the rest.
        // Checking the whole path rejects wire:model="form.files".
        if (is_null($property) || ! property_exists($this->livewire, str($property)->before('.')->value())) {
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

    protected function skeleton(int $default): int
    {
        $skeleton = $this->data('skeleton');

        return is_int($skeleton) ? $skeleton : $default;
    }

    protected function skeletonized(): bool
    {
        $skeleton = $this->data('skeleton');

        return $skeleton !== null && $skeleton !== false;
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
        // Mirrors property(): the path may be nested, only its head is a property.
        return $this->wireable() && ! is_null($property) && property_exists($this->livewire, str($property)->before('.')->value())
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
