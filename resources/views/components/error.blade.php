@props(['computed', 'error'])

@php($customize = tasteui_personalization('error', $customization()))

@error ($computed)
    <span @class($customize['base'])>
        {{ $message }}
    </span>
@enderror
