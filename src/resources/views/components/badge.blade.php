@php($personalize = tallstackui_personalization('badge', $personalization()))

<span {{ $attributes->class([
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.sm'] => $size == 'sm',
        $personalize['wrapper.sizes.md'] => $size == 'md',
        $personalize['wrapper.sizes.lg'] => $size == 'lg',
        $colors['wrapper.color'],
        'text-white' => $style === 'solid' && $color !== 'white',
        'rounded-md' => !$round && !$square,
        'rounded-full' => $round,
    ]) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class([
            $personalize['icon'],
            $colors['icon.color'],
            'mr-1' => $position === 'left',
        ]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class([
            $personalize['icon'],
            $colors['icon.color'],
            'ml-1' => $position === 'right',
        ]) />
    @endif
</span>
