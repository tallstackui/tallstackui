@php
    $personalize = $classes();
@endphp

<a href="{{ $formatted }}" {{ $attributes->class([
        'inline-flex',
        $personalize['bold'] => $bold,
        $personalize['underline'] => $underline,
        $personalize['italic'] => $italic,
        $personalize['icon.base'] => $icon,
        $personalize['sizes.'.$size],
        $colors['text'] => !$colorless,
    ]) }} @if ($blank) target="_blank" @endif @if ($navigate) wire:navigate @elseif ($navigateHover) wire:navigate.hover @endif>
    @if ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal class="{{ $personalize['icon.size'] }}" />
    @endif
    {!! $text ??= $slot->isNotEmpty() ? $slot : ($icon ? null : $href) !!}
    @if ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal class="{{ $personalize['icon.size'] }}" />
    @endif
</a>
