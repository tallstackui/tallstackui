@php
    $personalize = $classes();
@endphp

<a href="{{ $formatted }}" {{ $attributes->class([
        'inline-flex',
        $personalize['bold'] => $bold,
        $personalize['underline'] => $underline,
        $personalize['icon.base'] => $icon,
        $personalize['sizes.'.$size],
        $colors['text'] => !$colorless,
    ]) }} @if ($blank) target="_blank" @endif @if ($navigate) wire:navigate @elseif ($navigateHover) wire:navigate.hover @endif>
    @if ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class($personalize['icon.size']) />
    @endif
    {!! $text ?? ($slot->isNotEmpty() ? $slot : $href) !!}
    @if ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class($personalize['icon.size']) />
    @endif
</a>
