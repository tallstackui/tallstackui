@php($personalize = tallstackui_personalization('avatar', $personalization()))

<div {{ $attributes->class([
        $personalize['wrapper.class'],
        $colors['wrapper.color'],
        $personalize['wrapper.sizes.sm'] => $size === 'sm',
        $personalize['wrapper.sizes.md'] => $size === 'md',
        $personalize['wrapper.sizes.lg'] => $size === 'lg',
        'rounded-full' => !$square,
        'border-2' => !$model,
    ]) }}>
    @if ($model)
        <img @class([
            $personalize['content.image.class'],
            $personalize['content.image.sizes.sm'] => $size === 'sm',
            $personalize['content.image.sizes.md'] => $size === 'md',
            $personalize['content.image.sizes.lg'] => $size === 'lg',
            'rounded-full' => !$square
        ]) src="{{ $text }}" alt="{{ $alt() }}"/>
    @elseif ($text || $slot->isNotEmpty())
        <span @class([
                $personalize['content.text.class'],
                $personalize['content.text.colors.colorful'] => $color !== 'white',
                $personalize['content.text.colors.white'] => $color === 'white',
            ])>{{ $text ?? $slot }}</span>
    @else
        <svg @class([
                $personalize['content.text.class'],
                $personalize['content.text.colors.colorful'] => $color !== 'white',
                $personalize['content.text.colors.white'] => $color === 'white',
            ]) fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    @endif
</div>
