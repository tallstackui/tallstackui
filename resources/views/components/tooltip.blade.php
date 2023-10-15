@php($customize = tallstackui_personalization('tooltip', $personalization()))

<div @class($customize['wrapper']) x-data>
    <x-dynamic-component component="tallstack-ui::icon.{{ $style }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class([ $customize['sizes.sm'] => $size === 'sm', $customize['sizes.md'] => $size === 'md', $customize['sizes.lg'] => $size === 'lg', $colors['icon.color']]) }}
    />
</div>
