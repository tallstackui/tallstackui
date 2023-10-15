@php($customize = tallstackui_personalization('badge', $personalization()))

<span {{ $attributes->class([$customize['wrapper'], $colors['wrapper.color']]) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class([$customize['icon'], $colors['icon.color']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class([$customize['icon'], $colors['icon.color']]) />
    @endif
</span>
