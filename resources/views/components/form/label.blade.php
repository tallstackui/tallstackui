@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.form.label')->toArray();
    $customize       = tasteui_personalize($personalization, $customization($error));
@endphp

<div @class($customize['wrapper'])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($customize['text']) }}>
        {{ $text ?? $label ?? $slot }}
    </label>
</div>
