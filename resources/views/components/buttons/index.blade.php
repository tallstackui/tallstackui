@php($type = $href ? 'a' : 'button')

<{{ $type }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class([
        'outline-none inline-flex justify-center items-center group ease-in font-semibold',
        'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed text-sm',
        'gap-x-2'      => $icon !== null,
        'px-1 py-0.5'  => $size === 'xs',
        'px-2 py-1'    => $size === 'sm',
        'px-4 py-2'    => $size === 'md',
        'px-6 py-3'    => $size === 'lg',
        'rounded'      => $square === null && $round === null,
        'rounded-full' => $square === null && $round !== null,
        $color
    ]) }}>
    @if ($icon && $position === 'left')
        <x-icon :$icon solid @class([
            'text-white',
            'w-4 h-4' => $size !== 'lg',
            'w-6 h-6' => $size === 'lg',
        ]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon solid @class([
            'text-white',
            'w-4 h-4' => $size !== 'lg',
            'w-6 h-6' => $size === 'lg',
        ]) />
    @endif
</{{ $type }}>
