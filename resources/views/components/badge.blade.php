@php($customize = tallstackui_personalization('badge', $customization()))

<span {{ $attributes->class($customize['base']) }}>
    @if ($icon && $position == 'left')
        <x-icon :$icon @class($customize['icon']) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position == 'right')
        <x-icon :$icon @class($customize['icon']) />
    @endif
</span>
