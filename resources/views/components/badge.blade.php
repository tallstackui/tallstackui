@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.badge')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<span {{ $attributes->class($customize['main']) }}>
    {{ $text ?? $slot }}
</span>
