@php($customize = tallstackui_personalization('avatar', $personalization()))

<div {{ $attributes->class([
        $customize['wrapper.class'],
        $colors['wrapper.color'],
        $customize['wrapper.sizes.sm'] => $size === 'sm',
        $customize['wrapper.sizes.md'] => $size === 'md',
        $customize['wrapper.sizes.lg'] => $size === 'lg',
        'rounded-full' => !$square,
        'border-2' => !$modelable,
    ]) }}>
    @if ($modelable)
        <img @class([
            $customize['content.image'],
            $customize['content.image.sizes.sm'] => $size === 'sm',
            $customize['content.image.sizes.md'] => $size === 'md',
            $customize['content.image.sizes.lg'] => $size === 'lg',
            'rounded-full' => !$square
        ]) src="{{ $text }}" alt="{{ $alt() }}"/>
    @elseif ($text || $slot->isNotEmpty())
        <span @class([
                $customize['content.text.class'],
                $customize['content.text.colors.colorful'] => $color !== 'white',
                $customize['content.text.colors.white-black'] => $color === 'white' || $color === 'black',
            ])>{{ $text ?? $slot }}</span>
    @else
        <svg @class([
                $customize['content.text.class'],
                $customize['content.text.colors.colorful'] => $color !== 'white',
                $customize['content.text.colors.white-black'] => $color === 'white' || $color === 'black',
            ]) fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    @endif
</div>
