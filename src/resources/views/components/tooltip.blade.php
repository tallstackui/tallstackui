@php
    $personalize = $classes();
@endphp

@unless(blank($sentence))
    <div class="{{ $personalize['wrapper'] }}" x-data>
        <x-dynamic-component :component="$raw('tallstack-ui::icon.')"
                             data-position="{{ $position }}"
                             x-tooltip="{!! $sentence !!}"
                             {{ $attributes->class([$personalize['sizes.' . $size], $colors['icon']]) }} />
    </div>
@endunless
