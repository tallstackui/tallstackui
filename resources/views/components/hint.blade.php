@props(['hint' => null])

@php($customize = tasteui_personalization('hint', $customization()))

<span @class($customize['base'])>
    {{ $hint }}
</span>