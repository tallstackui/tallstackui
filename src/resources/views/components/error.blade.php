@php($customize = tallstackui_personalization('error', $personalization()))

@error ($computed)
    <span @class($customize['text'])>
        {{ $message }}
    </span>
@enderror
