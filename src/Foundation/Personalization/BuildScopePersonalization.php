<?php

namespace TallStackUi\Foundation\Personalization;

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

        // We format here to return only the classes that were actually defined
        // and thus be able to merge with the soft personalization definitions.
        return collect($this->attributes)->mapWithKeys(function ($value, $key) {
            return [$key => $this->classes[$key]];
        })->toArray();
    }

    private function append(): void
    {
        foreach ($this->attributes as $key => $value) {
            if (! is_array($value) || ! isset($value['append'])) {
                continue;
            }

            $this->classes[$key] = $this->classes[$key].' '.$value['append'];
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function common(): void
    {
        if (! $this->attributes) {
            return;
        }

        foreach ($this->attributes as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            $this->classes[$key] = $value;
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function prepend(): void
    {
        foreach ($this->attributes as $key => $value) {
            if (! is_array($value) || ! isset($value['prepend'])) {
                continue;
            }

            $this->classes[$key] = $value['prepend'].' '.$this->classes[$key];
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function remove(): void
    {
        foreach ($this->attributes as $key => $value) {
            if (! isset($value['remove'])) {
                continue;
            }

            $this->classes[$key] = str_replace($value['remove'], '', $this->classes[$key]);
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function replace(): void
    {
        if (! $this->attributes) {
            return;
        }

        foreach ($this->attributes as $key => $value) {
            if (! is_array($value) || ! isset($value['replace'])) {
                continue;
            }

            $this->classes[$key] = str_replace(array_keys($value['replace']), array_values($value['replace']), $this->classes[$key]);
        }
    }
}
