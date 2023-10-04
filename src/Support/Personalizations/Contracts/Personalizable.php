<?php

namespace TasteUi\Support\Personalizations\Contracts;

use Closure;
use TasteUi\Contracts\Personalizable as PersonalizableClass;

interface Personalizable
{
    /**
     * Get the content of a block.
     */
    public function get(string $block): ?string;

    /**
     * Get the content of a block as array.
     */
    public function toArray(): array;

    public function block(string|array $name, string|Closure|PersonalizableClass $code): static;
}
