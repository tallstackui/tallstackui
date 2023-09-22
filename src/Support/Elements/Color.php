<?php

namespace TasteUi\Support\Elements;

use InvalidArgumentException;

final class Color
{
    private const ACCEPTABLES = [
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

    public static function get(string $prefix, string $type, string $weight = null): string
    {
        $prefix = str_replace('-', '', $prefix);
        $type = str_replace('-', '', $type);

        throw_if(
            in_array($prefix, ['bg-', 'text-']),
            new InvalidArgumentException('Prefix must be bg- or text-')
        );

        throw_if(
            ! in_array($type, self::ACCEPTABLES),
            new InvalidArgumentException('Selected type is not allowed')
        );

        $base = '%s-%s-%s';

        if (! $weight) {
            $base = str($base)->beforeLast('%s');
        }

        return sprintf($base, $prefix, $type, $weight);
    }
}
