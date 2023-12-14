@php($personalize = $classes())

<span @class($personalize['text'])>
    {!! $hint ?? $slot !!}
</span>
