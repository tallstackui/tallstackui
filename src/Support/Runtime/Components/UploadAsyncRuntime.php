<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Form\Upload\Async\Component;
use TallStackUi\Support\Runtime\AbstractRuntime;

class UploadAsyncRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        /** @var Component $component */
        $component = $this->component;

        $bind = $this->bind();
        $property = $bind->get('property');
        $name = $component->attributes->get('name');

        if (! $property && ! $name) {
            __ts_validation_exception($component, 'The component requires either the [wire:model] or the [name] attribute.');
        }

        // Unlike every other form component, this one also renders outside of
        // Livewire, where there is neither a component to read nor an error bag.
        $value = $property && $this->wireable() ? $this->property($property) : null;

        if (empty($value) && ! empty($component->files)) {
            $value = $component->files;
        }

        $configuration = __ts_get_component_configuration(Component::class);

        $errors = $property && $this->errors
            ? $this->errors->get(is_array($value) ? $property.'.*' : $property)
            : [];

        return [
            'id' => $bind->get('id') ?? uniqid('tsui-upload-async-'),
            'property' => $property,
            'name' => $name,
            'route' => $component->route,
            'method' => strtoupper($component->method),
            'wireable' => $this->wireable(),
            // Resolved here instead of $this->getId() in the view because the
            // view also renders without any Livewire component around it.
            'wire' => $this->livewire?->getId(),
            // Mirrors Livewire itself: wire:model defers the round trip and
            // wire:model.live pushes the array to the server right away.
            'live' => $this->live(),
            'value' => $value ?: [],
            'invalid' => [
                'status' => $errors !== [],
                'quantity' => count($errors),
            ],
            'config' => [
                'chunk_size' => $component->chunkSize ?? $configuration['chunk_size'],
                'concurrency' => $component->concurrency ?? $configuration['concurrency'],
                'retries' => $component->retries ?? $configuration['retries'],
                'retry_delay' => $configuration['retry_delay'],
                'max_size' => $component->maxSize ?? $configuration['max_size'],
                'accept' => $component->accept ?? $configuration['accept'],
            ],
            'i18n' => trans('ts-ui::messages.upload_async'),
        ];
    }

    private function live(): bool
    {
        $attributes = $this->component->attributes;

        if (! $this->wireable() || ! $attributes::hasMacro('wire')) {
            return false;
        }

        return (bool) $attributes->wire('model')->hasModifier('live');
    }
}
