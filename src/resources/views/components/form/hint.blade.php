@php
    $personalize = $classes();
@endphp

<span @class($personalize['text'])>
    {!! $hint ?? $slot !!}
</span>
