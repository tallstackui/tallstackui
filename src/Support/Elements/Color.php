<?php

namespace TallStackUi\Support\Elements;

use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use Stringable;
use Throwable;

final class Color implements Stringable
{
    use Conditionable;

    private const COLORS = [
        'primary',
        'secondary',
        'yellow',
        'white',
        'black',
        'slate',
        'gray',
        'zinc',
        'neutral',
        'stone',
        'red',
        'orange',
        'amber',
        'lime',
        'green',
        'emerald',
        'teal',
        'cyan',
        'sky',
        'blue',
        'indigo',
        'violet',
        'purple',
        'fuchsia',
        'pink',
        'rose',
    ];

    private const PREFIXES = [
        'bg',
        'border',
        'ring',
        'text',
    ];

    protected string $color = '';

    protected string $class = '';

    public function __toString(): string
    {
        return $this->get();
    }

    public function set(string $prefix, string $type, int $weight = null): self
    {
        $prefix = str_replace('-', '', $prefix);
        $type = str_replace('-', '', $type);

        $this->validate($prefix, $type);

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

    /** @throws Throwable */
    private function validate(string $prefix, string $type): void
    {
        $prefix = str_replace(['hover:', 'ring:'], '', $prefix);

        throw_unless(
            in_array($prefix, self::PREFIXES),
            new InvalidArgumentException("Prefix type [$prefix] is not allowed")
        );

        throw_unless(
            in_array($type, self::COLORS),
            new InvalidArgumentException("Selected type [$type] is not allowed")
        );
    }
}
