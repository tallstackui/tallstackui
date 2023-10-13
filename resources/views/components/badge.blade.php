@php
    $customize = tallstackui_personalization('badge', $customization());
    $internal  = $internals();
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
