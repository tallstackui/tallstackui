@php($customize = tallstackui_personalization('form.error', $personalization()))

@error ($computed)
    <span @class($customize['text'])>
        {{ $message }}
    </span>
@enderror
