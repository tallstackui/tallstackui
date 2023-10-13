@props(['computed', 'error'])

@php($customize = tallstackui_personalization('error', $customization()))

@error ($computed)
<span @class($customize['text'])>
    {{ $message }}
</span>
@enderror
