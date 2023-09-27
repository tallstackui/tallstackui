@props(['computed', 'error'])

@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.error')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

@error ($computed)
    <span @class($customize['base'])>
        {{ $message }}
    </span>
@enderror
