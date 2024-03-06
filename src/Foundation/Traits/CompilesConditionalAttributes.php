<?php

namespace TallStackUi\Foundation\Traits;

use Illuminate\View\ComponentAttributeBag;

trait CompilesConditionalAttributes
{
    public function compile(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        return $this->attributes->when($boolean, fn (ComponentAttributeBag $attributes) => $attributes->merge($data, $escape));
    }
}
