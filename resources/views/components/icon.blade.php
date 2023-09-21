<x-dynamic-component component="taste-ui::icons.{{ $style }}.{{ $icon ?? $name }}" {{ $attributes->class(['text-red-500' => $error]) }} />
