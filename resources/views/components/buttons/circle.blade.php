@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.button.circle')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<button type="button" role="button" {{ $attributes->class($customize['base']) }}>
    <div @class($customize['wrapper'])>
        @if ($icon)
            <x-icon :$icon @class($customize['icon']) />
        @else
            {{ $text ?? $slot }}
        @endif
    </div>
</button>
