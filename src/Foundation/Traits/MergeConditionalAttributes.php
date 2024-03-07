<?php

namespace TallStackUi\Foundation\Traits;

use Illuminate\View\ComponentAttributeBag;

// The main purpose of this trait is to allow us to
// merge attributes that, without being merged, would
// require if/else in inputs through dynamic components.
trait MergeConditionalAttributes
{
    public function merge(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        return $this->attributes->when($boolean, fn (ComponentAttributeBag $attributes) => $attributes->merge($data, $escape));
    }
}
