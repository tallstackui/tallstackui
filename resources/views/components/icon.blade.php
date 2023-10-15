<x-dynamic-component component="tallstack-ui::icon.{{ $type }}.{{ $icon ?? $name }}" {{ $attributes->class(['text-red-500' => $error]) }} />
