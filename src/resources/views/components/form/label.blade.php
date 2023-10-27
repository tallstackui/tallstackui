@php
    $customize = tallstackui_personalization('form.label', $personalization());
    $text = $label ?? $slot;
    $asterisk = str($text)->endsWith(' *');

    if ($asterisk) $text = str($text)->beforeLast(' *');
@endphp

<div @class([$customize['wrapper'], $customize['error'] => $error])>
    <label @if ($for) for="{{ $for }}" @endif {{ $attributes->class($customize['text']) }}>
        {{ $text }}
        @if ($asterisk)
            <i @class($customize['asterisk'])>*</i>
        @endif
    </label>
</div>
