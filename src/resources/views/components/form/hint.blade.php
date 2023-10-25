@props(['hint' => null])

@php($customize = tallstackui_personalization('form.hint', $personalization()))

<span @class($customize['text'])>
    {{ $hint }}
</span>
