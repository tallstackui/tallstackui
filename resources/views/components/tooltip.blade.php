@php
    $customize = tallstackui_personalization('tooltip', $personalization());
    $internal = $internals();
@endphp

<div @class($customize['wrapper']) x-data>
    <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
            {{ $attributes->class([$customize['icon'], $internal['icon.color']]) }}
    />
</div>
