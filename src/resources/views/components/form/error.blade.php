@php($personalize = $classes())

@error ($computed)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
