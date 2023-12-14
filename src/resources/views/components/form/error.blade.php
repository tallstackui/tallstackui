@php($personalize = $classes())

@error ($wire)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
