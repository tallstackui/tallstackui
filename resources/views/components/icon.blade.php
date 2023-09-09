<x-dynamic-component component="taste-ui::icons.{{ $style }}.{{ $icon }}" {{ $attributes->class([
    'text-gray-500' => !$error,
    'text-red-500'  => $error,
]) }} />
