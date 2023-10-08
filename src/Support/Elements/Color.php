<?php

namespace TallStackUi\Support\Elements;

use Illuminate\Support\Traits\Conditionable;
use Stringable;

final class Color implements Stringable
{
    use Conditionable;

    protected string $color = '';

    protected string $class = '';

    private bool $clean = true;

    public function __toString(): string
    {
        return $this->get();
    }

    /** Avoid character cleaning within the `set` method. */
    public function clean(bool $clean = true): self
    {
        $this->clean = $clean;

        return $this;
    }

    public function set(string $prefix, string $type, int $weight = null): self
    {
        if ($this->clean) {
            $prefix = str_replace('-', '', $prefix);
            $type = str_replace('-', '', $type);
        }

        $base = '%s-%s-%s';

        if (! $weight) {
            $base = str($base)->beforeLast('%s');
        }

        $this->class = empty($this->class)
            ? sprintf($base, $prefix, $type, $weight)
            : ($this->class.' '.sprintf($base, $prefix, $type, $weight));

        if (! $weight) {
            $this->class = rtrim($this->class, '-');
        }

        return $this;
    }

    public function merge(string $prefix, string $type, int $weight = null): self
    {
        return $this->set($prefix, $type, $weight);
    }

    public function mergeWhen(bool $condition, string $prefix, string $type, int $weight = null): self
    {
        if ($condition) {
            $this->merge($prefix, $type, $weight);
        }

        return $this;
    }

    public function mergeUnless(bool $condition, string $prefix, string $type, int $weight = null): self
    {
        if (! $condition) {
            $this->merge($prefix, $type, $weight);
        }

        return $this;
    }

    public function prepend(string $class): self
    {
        $this->class = empty($this->class)
            ? $class
            : ($class.' '.$this->class);

        return $this;
    }

    public function append(string $class): self
    {
        $this->class = empty($this->class)
            ? $class
            : ($this->class.' '.$class);

        return $this;
    }

    public function get(): string
    {
        return $this->class;
    }
}
