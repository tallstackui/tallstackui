@php
    $customization = $classes();
@endphp

@if ($presence)
    <div class="{{ $customization['presence.base'] }}">
        @endif
        <div {{ $attributes->class([
            $customization['border.base'] => !$borderless && !$model,
            $customization['border.radius'] => !$square,
            $customization['wrapper.class'],
            $colors['background'] => !$model,
            $customization['wrapper.sizes.' . $size],
        ])->except('x-bind:src') }}>
            @if ($model || $image)
                <img @class([
                $customization['border.radius'] => !$square,
                $customization['content.image.class'],
                $customization['content.image.sizes.' . $size],
            ]) {{ $attributes->only('x-bind:src') }} src="{{ $image ?? $modelable() }}"
                     alt="{{ $text ?? $model?->getAttribute($property ?? null) }}" />
            @elseif ($text || $slot->isNotEmpty())
                <span @class([
                    $customization['content.text.class'],
                    $customization['content.text.colors.colorful'] => $color !== 'white',
                    $customization['content.text.colors.white'] => $color === 'white',
                ])>{!! $text ?? $slot !!}</span>
            @else
                <svg @class([
                    $customization['content.text.class'],
                    $customization['content.text.colors.colorful'] => $color !== 'white',
                    $customization['content.text.colors.white'] => $color === 'white',
                ]) fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            @endif
        </div>
        @if ($presence)
            <span @class([
            $customization['presence.wrapper'],
            $customization['presence.positions.' . $presencePosition],
        ])>
            @if ($pulse)
                    <span @class([
                    $customization['presence.ping'],
                    $colors['presence'],
                ])></span>
                @endif
            <span @class([
                'relative inline-flex',
                $customization['presence.dot'],
                $customization['presence.sizes.' . $size],
                $colors['presence'],
            ])></span>
        </span>
        @endif
        @if ($presence)
    </div>
@endif
