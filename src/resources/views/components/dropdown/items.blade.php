@php($personalize = tallstackui_personalization('dropdown.items', $personalization()))

<a {{ $attributes->class([
        'gap-x-2' => $icon,
        $personalize['item'],
        $personalize['border'] => $separator,
    ]) }} role="menuitem">
    @if ($icon && $position === 'left')
        <x-icon :$icon @class($personalize['icon']) />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right')
        <x-icon :$icon @class($personalize['icon']) />
    @endif
</a>
