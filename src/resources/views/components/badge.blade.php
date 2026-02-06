@php
    $personalize = $classes();
@endphp

<span {{ $attributes->class([
        $personalize['border.radius.rounded'] => !$round && !$square,
        $personalize['border.radius.circle'] => $round,
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.' . $size],
        $colors['background'],
        $colors['text'],
        $personalize['clickable'] => $attributes->hasAny(['wire:click', 'x-on:click']),
    ]) }}>
    @if ($left)
        {{ $left }}
    @elseif ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             @class(['mr-1' => $position === 'left', $personalize['icon'], $colors['icon']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($right)
        {{ $right }}
    @elseif ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             @class(['ml-1' => $position === 'right', $personalize['icon'], $colors['icon']]) />
    @endif
</span>
