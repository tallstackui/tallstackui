<?php

namespace TasteUi\Support\Personalizations\Contracts;

interface Personalizable
{
    /**
     * Get the content of a block.
     */
    public function get(string $block): ?string;

    /**
     * Set the content of a block.
     */
    public function set(string $block, string $content): void;

    /**
     * Get the component class.
     */
    public function component(): string;

    /**
     * Get the content of a block as array.
     */
    public function toArray(): array;
}
