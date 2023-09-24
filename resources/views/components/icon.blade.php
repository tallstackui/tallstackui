<x-dynamic-component component="taste-ui::icons.{{ $type }}.{{ $icon ?? $name }}" {{ $attributes->class(['text-red-500' => $error]) }} />
