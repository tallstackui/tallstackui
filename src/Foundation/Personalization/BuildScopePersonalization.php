<?php

namespace TallStackUi\Foundation\Personalization;

use Illuminate\Support\Collection;

class BuildScopePersonalization
{
    public function __construct(
        private array $classes,
        private readonly ?array $attributes = null,
        private ?Collection $collection = null,
    ) {
        $this->collection = collect($this->attributes);
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
        return $this->collection->mapWithKeys(fn (string|array $value, string $key) => [$key => $this->classes[$key] ?? []])->toArray();
    }

    private function append(): void
    {
        $attributes = $this->collection->filter(fn (string|array $value) => is_array($value) && isset($value['append']))->toArray();

        foreach ($attributes as $key => $value) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = $this->classes[$key].' '.$value['append'];
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function common(): void
    {
        $attributes = $this->collection->filter(fn (string|array $value) => is_string($value))->toArray();

        foreach ($attributes as $key => $value) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = $value;
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function prepend(): void
    {
        $attributes = $this->collection->filter(fn (string|array $value) => is_array($value) && isset($value['prepend']))->toArray();

        foreach ($attributes as $key => $value) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = $value['prepend'].' '.$this->classes[$key];
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function remove(): void
    {
        $attributes = $this->collection->filter(fn (string|array $value) => is_array($value) && isset($value['remove']))->toArray();

        foreach ($attributes as $key => $value) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = str_replace($value['remove'], '', (string) $this->classes[$key]);
            $this->classes[$key] = str($this->classes[$key])->squish()->trim()->value();
        }
    }

    private function replace(): void
    {
        $attributes = $this->collection->filter(fn (string|array $value) => is_array($value) && isset($value['replace']))->toArray();

        foreach ($attributes as $key => $value) {
            if (! isset($this->classes[$key])) {
                continue;
            }

            $this->classes[$key] = str_replace(array_keys($value['replace']), array_values($value['replace']), (string) $this->classes[$key]);
        }
    }
}
