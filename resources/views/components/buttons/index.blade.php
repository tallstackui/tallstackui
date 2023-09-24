@php($type = $href ? 'a' : 'button')

<{{ $type }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class($baseClass()) }}>
    @if ($icon && $position === 'left')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($iconClass())
        />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($iconClass())
        />
    @endif
</{{ $type }}>
