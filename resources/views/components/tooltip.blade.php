@php($customize = tasteui_personalization('tooltip', $customization()))

<div @class($customize['wrapper']) x-data>
    <x-dynamic-component component="taste-ui::icons.{{ $style }}.{{ $icon }}"
                         data-position="{{ $position }}"
                         x-tooltip="{!! $text !!}"
                        {{ $attributes->class($customize['icon']) }}
    />
</div>
