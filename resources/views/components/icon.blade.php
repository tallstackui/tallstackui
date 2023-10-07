<x-dynamic-component component="tallstack-ui::icons.{{ $type }}.{{ $icon ?? $name }}" {{ $attributes->class(['text-red-500' => $error]) }} />
