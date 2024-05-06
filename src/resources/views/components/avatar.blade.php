@php
    $personalize = $classes();
@endphp

<div {{ $attributes->class([
        'rounded-full' => !$square,
        'border-2' => !$model,
        $personalize['wrapper.class'],
        $colors['background'] => !$model,
        $personalize['wrapper.sizes.' . $size],
    ]) }}>
    @if ($model)
        <img @class([
            'rounded-full' => !$square,
            $personalize['content.image.class'],
            $personalize['content.image.sizes.' . $size],
        ]) src="{{ $modelable() }}" alt="{{ $model->getAttribute($property ?? null) }}"/>
    @elseif ($text || $slot->isNotEmpty())
        <span @class([
                $personalize['content.text.class'],
                $personalize['content.text.colors.colorful'] => $color !== 'white',
                $personalize['content.text.colors.white'] => $color === 'white',
            ])>{!! $text ?? $slot !!}</span>
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
