@php
    $customize = tallstackui_personalization('tooltip', $personalization());
    $sizes = $customize['sizes.' . $size];
@endphp

<div @class($customize['wrapper']) x-data>
    <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class([
                            'focus:outline-none',
                            $sizes,
                            $colors['icon.color']
                        ]) }}
    />
</div>
