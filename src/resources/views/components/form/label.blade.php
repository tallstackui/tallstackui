@php
    $personalize = $classes();
    $text = $label ?? $slot;
    $asterisk = str($text)->endsWith(' *');
    if ($asterisk) $text = str($text)->beforeLast(' *');
@endphp

<label @if ($for) for="{{ $for }}" @endif @class([$personalize['text'], $personalize['error'] => $error && $validate])>
    {{ $text }}
    @if ($asterisk)
        <span @class($personalize['asterisk'])>*</span>
    @endif
</label>
