@php($personalize = tallstackui_personalization('form.error', $personalization()))

@error ($computed)
    <span @class($personalize['text'])>
        {{ $message }}
    </span>
@enderror
