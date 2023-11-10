<?php

namespace TallStackUi\View\Personalizations\Contracts;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use TallStackUi\Contracts\Personalizable;

interface PersonalizableResources extends Arrayable
{
    public function block(string|array $name, string|null|Closure|Personalizable $code = null): self;

    public function get(string $block): ?string;

    public function toArray(): array;
}
