@php($personalize = $classes())

@error ($bind)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
