@php
    $personalize = $classes();
@endphp

@error ($property)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
