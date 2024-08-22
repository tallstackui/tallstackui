<?php

namespace TallStackUi\Foundation\Support\Concerns;

use Illuminate\View\ComponentAttributeBag;

// The main purpose of this trait is to allow us to
// merge attributes that, without being merged, would
// require if/else in inputs through dynamic components.
trait MergeAttributes
{
    /**
     * Merge attributes.
     */
    public function merge(array $data = [], bool $escape = true): ComponentAttributeBag
    {
        return $this->attributes->merge($data, $escape);
    }

    /**
     * Merge attributes unless a condition is met.
     */
    public function mergeUnless(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        if (! $boolean) {
            return $this->merge($data, $escape);
        }

        return $this->attributes;
    }

    /**
     * Merge attributes when a condition is met.
     */
    public function mergeWhen(bool $boolean, array $data = [], bool $escape = true): ComponentAttributeBag
    {
        if ($boolean) {
            return $this->merge($data, $escape);
        }

        return $this->attributes;
    }
}
