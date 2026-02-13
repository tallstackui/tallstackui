<?php

namespace TallStackUi\Support\CommandPalette;

use Illuminate\Contracts\Support\Arrayable;

class ItemSelected implements Arrayable
{
    public function __construct(
        public readonly string $search,
        public readonly mixed $label,
        public readonly mixed $value,
        public readonly ?string $description = null,
        public readonly ?string $image = null,
        public readonly ?string $icon = null,
        public readonly array $additional = [],
    ) {
        //
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
