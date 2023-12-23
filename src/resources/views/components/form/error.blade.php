@php($personalize = $classes())

@error ($property)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
