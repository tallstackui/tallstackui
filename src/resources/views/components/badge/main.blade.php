@php
    $customization = $classes();
@endphp

<span {{ $attributes->class([
        $customization['border.radius.' . $rounded] => !$square,
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
                             @class([$customization['icon-spacing.left'] => $position === 'left', $customization['icon'], $colors['icon']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($right)
        {{ $right }}
    @elseif ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             @class([$customization['icon-spacing.right'] => $position === 'right', $customization['icon'], $colors['icon']]) />
    @endif
</span>
