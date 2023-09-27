@props(['computed', 'error'])

@php($customize = tasteui_personalization('error', $customization($error)))

@error ($computed)
<span @class($customize['base'])>
        {{ $message }}
    </span>
@enderror
