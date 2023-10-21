@props(['hint' => null])

@php($customize = tallstackui_personalization('hint', $personalization()))

<span @class($customize['text'])>
    {{ $hint }}
</span>
