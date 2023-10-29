@php
    $personalize = tallstackui_personalization('form.label', $personalization());
    $text = $label ?? $slot;
    $asterisk = str($text)->endsWith(' *');

    if ($asterisk) $text = str($text)->beforeLast(' *');
@endphp

<div @class([$personalize['wrapper'], $personalize['error'] => $error])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($personalize['text']) }}>
        {{ $text }}
        @if ($asterisk)
            <i @class($personalize['asterisk'])>*</i>
        @endif
    </label>
</div>
