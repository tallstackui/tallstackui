<?php

namespace TasteUi\Support\Personalizations\Contracts;

interface Personalizable
{
    public function set(string $block, string $content): void;

    public function get(string $block): ?string;

    public function component(): string;
}
