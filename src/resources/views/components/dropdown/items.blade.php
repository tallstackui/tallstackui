@php
    $personalize = $classes();
@endphp

<span {{ $attributes->class([
        'gap-x-2' => $icon,
        $personalize['item'],
        $personalize['border'] => $separator,
    ]) }} role="menuitem">
    @if ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class($personalize['icon']) />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class($personalize['icon']) />
    @endif
</span>
