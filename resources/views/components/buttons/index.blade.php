@php
    $type = $href ? 'a' : 'button';

    $customize = $customize();

    $customize['main'] ??= $customMainClasses();
    $customize['icon'] ??= $customIconClasses();
@endphp

<{{ $type }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class($customize['main']) }}>
    @if ($icon && $position === 'left')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($customize['icon'])
        />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon
                type="{{ config('tasteui.icon') ?? 'solid' }}"
                @class($customize['icon'])
        />
    @endif
</{{ $type }}>
