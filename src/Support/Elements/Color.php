<?php

namespace TasteUi\Support\Elements;

use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use Stringable;
use Throwable;

final class Color implements Stringable
{
    use Conditionable;

    protected string $color = '';

    protected string $class = '';

    /**
     * Acceptables tailwindcss colors.
     */
    private const ACCEPTABLES_COLORS = [
        'primary',
        'secondary',
        'positive',
        'negative',
        'warning',
        'info',
        'dark',
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

    /**
     * Acceptables tailwindcss prefixes.
     */
    private const ACCEPTABLES_PREFIXES = [
        'bg',
        'border',
        'ring',
        'text',
    ];

    /**
     * Compile classes to the `$class`.
     *
     * @return $this
     */
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

    /**
     * Merge classes into `$class`.
     *
     * @return $this
     *
     * @throws Throwable
     */
    public function merge(string $prefix, string $type, int $weight = null): self
    {
        return $this->set($prefix, $type, $weight);
    }

    /**
     * Merge classes into `$class` when condition is true.
     *
     * @return $this
     */
    public function mergeWhen(bool $condition, string $prefix, string $type, int $weight = null): self
    {
        if ($condition) {
            $this->merge($prefix, $type, $weight);
        }

        return $this;
    }

    /**
     * Merge classes into `$class` when condition is false.
     *
     * @return $this
     */
    public function mergeUnless(bool $condition, string $prefix, string $type, int $weight = null): self
    {
        if (! $condition) {
            $this->merge($prefix, $type, $weight);
        }

        return $this;
    }

    /**
     * Manual append classes into `$class`.
     */
    public function prepend(string $class): self
    {
        $this->class = empty($this->class)
            ? $class
            : ($class.' '.$this->class);

        return $this;
    }

    /**
     * Manual append classes into `$class`.
     */
    public function append(string $class): self
    {
        $this->class = empty($this->class)
            ? $class
            : ($this->class.' '.$class);

        return $this;
    }

    /**
     * Get the compiled classes.
     */
    public function get(): string
    {
        return $this->class;
    }

    /**
     * Get the compiled classes.
     */
    public function __toString(): string
    {
        return $this->get();
    }

    /**
     * Validate the prefix and type.
     *
     * @throws Throwable
     */
    private function validate(string $prefix, string $type)
    {
//        $prefix = str_replace(['hover:', 'ring:'], '', $prefix);
//
//        throw_unless(
//            in_array($prefix, self::ACCEPTABLES_PREFIXES),
//            new InvalidArgumentException("Prefix type is not allowed: [$prefix]")
//        );
//
//        throw_unless(
//            in_array($type, self::ACCEPTABLES_COLORS),
//            new InvalidArgumentException("Selected type is not allowed: [$type]")
//        );
    }
}
