@props(['hint' => null])

@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.hint')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<span @class($customize['base'])>
    {{ $hint }}
</span>