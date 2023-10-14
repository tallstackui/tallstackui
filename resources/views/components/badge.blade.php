@php
    $customize = tallstackui_personalization('badge', $personalization());
    $internal = $internals();
@endphp

<span {{ $attributes->class([$customize['wrapper'], $internal['wrapper.color']]) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class([$customize['icon'], $internal['icon.color']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class([$customize['icon'], $internal['icon.color']]) />
    @endif
</span>
