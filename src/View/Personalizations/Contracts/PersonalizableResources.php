<?php

namespace TallStackUi\View\Personalizations\Contracts;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use TallStackUi\Contracts\Personalizable;

interface PersonalizableResources extends Arrayable
{
    public function block(string|array $name, string|Closure|Personalizable $code = null): static;

    public function get(string $block): ?string;

    public function toArray(): array;
}
