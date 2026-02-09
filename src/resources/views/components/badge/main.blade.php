@php
    $customization = $classes();
@endphp

<span {{ $attributes->class([
        $customization['border.radius.rounded'] => !$round && !$square,
        $customization['border.radius.circle'] => $round,
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $colors['background'],
        $colors['text'],
        $customization['clickable'] => $attributes->hasAny(['wire:click', 'x-on:click']),
    ]) }}>
    @if ($left)
        {{ $left }}
    @elseif ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             @class(['mr-1' => $position === 'left', $customization['icon'], $colors['icon']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($right)
        {{ $right }}
    @elseif ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             @class(['ml-1' => $position === 'right', $customization['icon'], $colors['icon']]) />
    @endif
</span>
