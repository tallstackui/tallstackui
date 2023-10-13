@props(['hint' => null])

@php($customize = tallstackui_personalization('hint', $customization()))

<span @class($customize['text'])>
    {{ $hint }}
</span>
