<?php

namespace TasteUi\Support\Personalizations\Contracts;

use Closure;
use TasteUi\Contracts\Personalizable as PersonalizableClass;

interface Personalizable
{
    public function get(string $block): ?string;

    public function toArray(): array;

    public function block(string|array $name, string|Closure|PersonalizableClass $code = null): static;
}
