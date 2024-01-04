<?php

namespace TallStackUi\Foundation\Personalization;

use Illuminate\Support\Collection;

class BuildScopePersonalization
{
    private ?array $attributes;

    private array $classes;

    public function __construct(array $classes, ?array $attributes = null)
    {
        $this->classes = $classes;
        $this->attributes = $attributes;
    }

    public function __invoke(): array
    {
        if ($this->attributes) {
            $this->common();
            $this->replace();
            $this->remove();
            $this->append();
            $this->prepend();
        }

        return $this->classes;
    }

    private function collection(): Collection
    {
        return collect(array_values($this->attributes));
    }

    private function arrays(): array
    {
        return $this->collection()->filter(fn ($value) => is_array($value))->first();
    }

    private function append(): void
    {
        if (! $this->attributes) {
            return;
        }

        $append = $this->collection()->filter(fn ($value) => is_array($value))->first()['append'] ?? null;

        if (blank($append)) {
            return;
        }

        $positions = collect($this->attributes)->filter(fn ($value) => ! is_array($append))->keys();

        foreach ($positions as $position) {
            $this->classes[$position] = $this->classes[$position].' '.$append;
        }
    }

    private function common(): void
    {
        if (! $this->attributes) {
            return;
        }

        $values = $this->collection()->filter(fn ($value) => ! is_array($value));

        $positions = collect($this->attributes)->filter(fn ($value) => ! is_array($value))->keys();

        foreach ($positions as $key) {
            $this->classes[$key] = $values->first();
        }
    }

    private function prepend(): void
    {
        if (! $this->attributes) {
            return;
        }

        $prepend = $this->collection()->filter(fn ($value) => is_array($value))->first()['prepend'] ?? null;

        if (blank($prepend)) {
            return;
        }

        $positions = collect($this->attributes)->filter(fn ($value) => ! is_array($prepend))->keys();

        foreach ($positions as $position) {
            $this->classes[$position] = $prepend.' '.$this->classes[$position];
        }
    }

    private function remove(): void
    {
        if (! $this->attributes) {
            return;
        }

        $removes = $this->collection()->filter(fn ($value) => is_array($value))->first()['remove'] ?? null;

        if (blank($removes)) {
            return;
        }

        $positions = collect($this->attributes)->filter(fn ($value) => is_array($value))->keys();

        foreach ($positions as $key) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = str_replace($removes, '', $this->classes[$key]);
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function replace(): void
    {
        if (! $this->attributes) {
            return;
        }

        $replaces = $this->collection()->filter(fn ($value) => is_array($value))->first()['replace'] ?? null;

        if (blank($replaces)) {
            return;
        }

        $positions = collect($this->attributes)->filter(fn ($value) => is_array($value))->keys();

        foreach ($positions as $key) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = str_replace(array_keys($replaces), array_values($replaces), $this->classes[$key]);
        }
    }
}
