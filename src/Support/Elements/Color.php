<?php

namespace TasteUi\Support\Elements;

use InvalidArgumentException;
use Stringable;
use Throwable;

final class Color implements Stringable
{
    protected string $class = '';

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

    private const ACCEPTABLES_PREFIXES = [
        'bg-',
        'text-',
    ];

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

        return $this;
    }

    public function merge(string $prefix, string $type, string $weight = null): self
    {
        return $this->set($prefix, $type, $weight);
    }

    public function get(): string
    {
        return $this->class;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    /** @throws Throwable */
    private function validate(string $prefix, string $type)
    {
        throw_if(
            in_array($prefix, self::ACCEPTABLES_PREFIXES),
            new InvalidArgumentException('Prefix type is not allowed')
        );

        throw_unless(
            in_array($type, self::ACCEPTABLES_COLORS),
            new InvalidArgumentException('Selected type is not allowed')
        );
    }
}
