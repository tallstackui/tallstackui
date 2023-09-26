<?php

namespace TasteUi\Support\Personalizations\Traits;

use Illuminate\Support\Collection;

trait ShareablePersonalization
{
    public function __construct(
        public ?Collection $parts = null,
    ) {
        $this->parts = collect();
    }

    public function set(string $block, string $content): void
    {
        $this->parts[$block] = $content;
    }

    public function get(string $block): ?string
    {
        return data_get($this->parts, $block);
    }

    public function toArray(): array
    {
        return $this->parts->toArray();
    }
}
