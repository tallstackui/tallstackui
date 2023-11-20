@php
    $personalize = tallstackui_personalization('form.label', $personalization());
    $text = $label ?? $slot;
    $asterisk = str($text)->endsWith(' *');

    if ($asterisk) $text = str($text)->beforeLast(' *');
@endphp

<label @if ($for) for="{{ $for }}" @endif @class([$personalize['text'], $personalize['error'] => $error])>
    {{ $text }}
    @if ($asterisk)
        <span @class($personalize['asterisk'])>*</span>
    @endif
</label>
