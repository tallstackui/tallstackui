@php($customize = tallstackui_personalization('dropdown.items', $personalization()))

<a {{ $attributes->class($customize['item']) }} role="menuitem" tabindex="-1" id="menu-item-0">
    @if ($icon && $position === 'left')
        <x-icon :$icon @class($customize['icon']) />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right')
        <x-icon :$icon @class($customize['icon']) />
    @endif
</a>
