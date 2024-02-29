@php
    $personalize = $classes();
    $text ??= $slot->toHtml();
@endphp

@unless(blank($text))
    <div @class($personalize['wrapper']) x-data>
        <x-dynamic-component :component="$icon('tallstack-ui::icon.')"
                             data-position="{{ $position }}"
                             x-tooltip="{!! $text !!}"
                             {{ $attributes->class([$personalize['sizes.' . $size], $colors['icon']]) }} />
    </div>
@endunless
