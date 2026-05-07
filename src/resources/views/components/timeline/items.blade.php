@aware([
    'color' => 'primary',
    'style' => 'solid',
    'horizontal' => false,
    'alternate' => false,
    'compact' => false,
    'reversed' => false,
])

@php
    $customization = $classes();
@endphp

<div
    {{ $attributes->class([
        $customization['wrapper.grid-vertical'] => $alternate && ! $horizontal,
        $customization['wrapper.grid-horizontal'] => $alternate && $horizontal,
        $customization['wrapper.flex-vertical'] => ! $alternate && ! $horizontal,
        $customization['wrapper.flex-horizontal'] => ! $alternate && $horizontal,
    ]) }}
    dusk="tallstackui_timeline_item"
>
    @if ($horizontal)
        <span
            aria-hidden="true"
            data-timeline-line-left
            @class([
                $customization['line.grid-horizontal-left'] => $alternate && ! $compact,
                $customization['line.grid-horizontal-left-compact'] => $alternate && $compact,
                $customization['line.flex-horizontal-left'] => ! $alternate && ! $compact,
                $customization['line.flex-horizontal-left-compact'] => ! $alternate && $compact,
                $colors['line'] ?? '' => true,
            ])
        ></span>
        <span
            aria-hidden="true"
            data-timeline-line-right
            @class([
                $customization['line.grid-horizontal-right'] => $alternate && ! $compact,
                $customization['line.grid-horizontal-right-compact'] => $alternate && $compact,
                $customization['line.flex-horizontal-right'] => ! $alternate && ! $compact,
                $customization['line.flex-horizontal-right-compact'] => ! $alternate && $compact,
                $colors['line'] ?? '' => true,
            ])
        ></span>
    @else
        <span
            aria-hidden="true"
            data-timeline-line
            @class([
                $customization['line.grid-vertical'] => $alternate && ! $compact,
                $customization['line.grid-vertical-compact'] => $alternate && $compact,
                $customization['line.flex-vertical'] => ! $alternate && ! $compact,
                $customization['line.flex-vertical-compact'] => ! $alternate && $compact,
                $colors['line'] ?? '' => true,
            ])
        ></span>
    @endif

    <div @class([
        $customization['marker.wrapper'],
        $customization['marker.grid-vertical'] => $alternate && ! $horizontal,
        $customization['marker.grid-horizontal'] => $alternate && $horizontal,
        $colors['marker'] ?? '' => true,
    ])>
        @if ($content['marker'] ?? null)
            <span class="{{ $customization['marker.custom'] }}">
                {!! $content['marker'] !!}
            </span>
        @elseif ($icon)
            <span class="{{ $customization['marker.icon-wrapper'] }}">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :$icon
                                     internal
                                     @class([$customization['marker.icon-size'], $colors['icon'] ?? '']) />
            </span>
        @else
            <span @class([$customization['marker.bullet-base'], $customization['marker.bullet']])></span>
        @endif
    </div>

    <div @class([
        $customization['content.base'] => ! $alternate,
        $customization['content.flex-vertical'] => ! $alternate && ! $horizontal,
        $customization['content.flex-horizontal'] => ! $alternate && $horizontal,
        $customization['content.grid-vertical-normal'] => $alternate && ! $horizontal && ! $reversed,
        $customization['content.grid-vertical-reversed'] => $alternate && ! $horizontal && $reversed,
        $customization['content.grid-horizontal-normal'] => $alternate && $horizontal && ! $reversed,
        $customization['content.grid-horizontal-reversed'] => $alternate && $horizontal && $reversed,
    ])>
        @if ($date)
            <time class="{{ $customization['date'] }}">{{ $date }}</time>
        @endif
        @if ($title)
            <h3 class="{{ $customization['title'] }}">{{ $title }}</h3>
        @endif
        @if ($description)
            <p class="{{ $customization['description'] }}">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
</div>
