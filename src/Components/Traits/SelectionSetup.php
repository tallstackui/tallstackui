<?php

namespace TallStackUi\Components\Traits;

use Illuminate\Support\Collection;

trait SelectionSetup
{
    protected function setup(): void
    {
        $this->columns ??= 3;

        $this->position = match ($this->position) {
            'right' => 'right',
            default => 'left',
        };

        $this->variant = match (true) {
            $this->card !== null => 'card',
            $this->panel !== null => 'panel',
            $this->inline !== null => 'inline',
            default => 'list',
        };

        $this->size = match (true) {
            $this->xs !== null => 'xs',
            $this->sm !== null => 'sm',
            $this->lg !== null => 'lg',
            default => 'md',
        };

        $this->options = $this->options instanceof Collection
            ? $this->options->values()->toArray()
            : array_values($this->options);

        $this->selectable = $this->selectable();

        $this->options = collect($this->options)
            ->map(fn (mixed $option, int $index): array => $this->normalize($option, $index))
            ->toArray();
    }

    protected function validate(): void
    {
        if ($this->columns < 1 || $this->columns > 4) {
            __ts_validation_exception($this, 'The [columns] must be between 1 and 4.');
        }
    }

    /** Normalize an option, keeping the original keys for the interact slot. */
    private function normalize(mixed $option, int $index): array
    {
        if (! is_array($option)) {
            __ts_validation_exception($this, 'The [options] must be an array of arrays.');
        }

        foreach (['label', 'value'] as $mandatory) {
            if (! array_key_exists($key = $this->selectable[$mandatory], $option)) {
                __ts_validation_exception($this, "The key [{$key}] is missing in the options array.");
            }
        }

        return [
            ...$option,
            'label' => $option[$this->selectable['label']],
            'value' => $option[$this->selectable['value']],
            'description' => $option[$this->selectable['description']] ?? null,
            'aside' => $option[$this->selectable['aside']] ?? null,
            'icon' => $option[$this->selectable['icon']] ?? null,
            'image' => $option[$this->selectable['image']] ?? null,
            'badge' => $option[$this->selectable['badge']] ?? null,
            'disabled' => (bool) ($option['disabled'] ?? false),
            '__tsui_key' => $index,
        ];
    }

    /** Resolve the option keys, remapped through `select` when given. */
    private function selectable(): array
    {
        $keys = ['label', 'value', 'description', 'aside', 'icon', 'image', 'badge'];

        $this->select ??= __ts_get_component_configuration(static::class, 'select');

        $select = array_reduce(
            explode('|', (string) $this->select),
            function (array $result, string $item): array {
                $parts = explode(':', $item, 2);

                if (count($parts) === 2) {
                    $result[trim($parts[0])] = trim($parts[1]);
                }

                return $result;
            },
            []
        );

        return collect($keys)
            ->mapWithKeys(fn (string $key): array => [$key => $select[$key] ?? $key])
            ->toArray();
    }
}
