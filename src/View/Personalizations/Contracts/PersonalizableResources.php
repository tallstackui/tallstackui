<?php

namespace TallStackUi\View\Personalizations\Contracts;

use Closure;
use TallStackUi\Contracts\Personalizable;

interface PersonalizableResources
{
    public function block(string|array $name, string|Closure|Personalizable $code = null): static;

    public function get(string $block): ?string;

    public function toArray(): array;
}
