@php($customize = tallstackui_personalization('badge', $personalization()))

<span {{ $attributes->class([
        $customize['wrapper.class'],
        $customize['wrapper.sizes.sm'] => $size == 'sm',
        $customize['wrapper.sizes.md'] => $size == 'md',
        $customize['wrapper.sizes.lg'] => $size == 'lg',
        $colors['wrapper.color'],
        'text-white' => $color !== 'white' && $style === 'solid',
        'rounded-md' => !$round && !$square,
        'rounded-full' => $round,
    ]) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class([
            $customize['icon'],
            $colors['icon.color'],
            'mr-1' => $position === 'left',
        ]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class([
            $customize['icon'],
            $colors['icon.color'],
            'ml-1' => $position === 'right',
        ]) />
    @endif
</span>
