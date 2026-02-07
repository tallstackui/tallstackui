@php
    $customization = $classes();
@endphp

@unless(blank($sentence))
    <div class="{{ $customization['wrapper'] }}" x-data>
        <x-dynamic-component :component="$raw('ts-ui::icon.')"
                             data-position="{{ $position }}"
                             x-tooltip="{!! $sentence !!}"
                {{ $attributes->class([$customization['sizes.' . $size], $colors['icon']]) }} />
    </div>
@endunless
