<?php

namespace TallStackUi\Foundation\Traits;

use Illuminate\View\ComponentAttributeBag;

// The main purpose of this trait is to allow us to
// merge attributes that, without being merged, would
// require if/else in inputs through dynamic components.
trait MergeAttributes
{
    public function merge(array $data = [], bool $escape = true): ComponentAttributeBag
    {
        return $this->attributes->merge($data, $escape);
    }

    public function mergeUnless(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        if (! $boolean) {
            return $this->merge($data, $escape);
        }

        return $this->attributes;
    }

    public function mergeWhen(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        if ($boolean) {
            return $this->merge($data, $escape);
        }

        return $this->attributes;
    }
}
