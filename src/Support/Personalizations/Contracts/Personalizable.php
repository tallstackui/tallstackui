<?php

namespace TasteUi\Support\Personalizations\Contracts;

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
}
