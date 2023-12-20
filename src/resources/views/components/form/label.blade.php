@php
    $personalize = $classes();
    $text = $label ?? $slot;
    $asterisk = str($text)->endsWith(' *');
    if ($asterisk) $text = str($text)->beforeLast(' *');
@endphp

<label @if ($id) for="{{ $id }}" @endif @class([$personalize['text'], $personalize['error'] => $error && !$invalidate])>
    {{ $text }}
    @if ($asterisk)
        <span @class($personalize['asterisk'])>*</span>
    @endif
</label>
