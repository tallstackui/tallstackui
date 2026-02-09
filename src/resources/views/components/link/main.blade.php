@php
    $customization = $classes();
@endphp

<a href="{{ $formatted }}" {{ $attributes->class([
        'inline-flex',
        $customization['bold'] => $bold,
        $customization['underline'] => $underline,
        $customization['italic'] => $italic,
        $customization['icon.base'] => $icon,
        $customization['sizes.'.$size],
        $colors['text'] => !$colorless,
    ]) }} @if ($blank) target="_blank" @endif @if ($navigate) wire:navigate
   @elseif ($navigateHover) wire:navigate.hover @endif>
    @if ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal
                             class="{{ $customization['icon.size'] }}" />
    @endif
    {!! $text ??= $slot->isNotEmpty() ? $slot : ($icon ? null : $href) !!}
    @if ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal
                             class="{{ $customization['icon.size'] }}" />
    @endif
</a>
