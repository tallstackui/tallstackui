<?php

namespace TallStackUi\View\Personalizations\Contracts;

use Closure;
use TallStackUi\Contracts\Personalizable as PersonalizableClass;

interface Personalizable
{
    public function block(string|array $name, string|Closure|PersonalizableClass $code = null): static;

    public function get(string $block): ?string;

    public function toArray(): array;
}
