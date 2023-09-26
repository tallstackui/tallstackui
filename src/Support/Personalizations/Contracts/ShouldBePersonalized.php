<?php

namespace TasteUi\Support\Personalizations\Contracts;

interface ShouldBePersonalized
{
    public function set(string $block, string $content): void;

    public function get(string $block): ?string;
}
