@php
    $personalize = tallstackui_personalization('tooltip', $personalization());
    $sizes = $personalize['sizes.' . $size];
@endphp

<div @class($personalize['wrapper']) x-data>
    <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class([
                            'focus:outline-none',
                            $sizes,
                            $colors['icon']
                        ]) }}
    />
</div>
