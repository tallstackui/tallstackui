@props(['computed', 'error'])

@php($customize = tallstackui_personalization('error', $customization()))

@error ($computed)
<span @class($customize['base'])>
        {{ $message }}
    </span>
@enderror
