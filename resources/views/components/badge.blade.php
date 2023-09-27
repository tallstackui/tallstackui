@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.badge')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<span {{ $attributes->class($customize['base']) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class($customize['icon']) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class($customize['icon']) />
    @endif
</span>
