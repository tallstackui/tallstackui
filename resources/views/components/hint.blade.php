@props(['hint' => null])

@php($customize = tallstackui_personalization('hint', $customization()))

<span @class($customize['base'])>
    {{ $hint }}
</span>
