@php
    $personalize = $classes();
@endphp

<span {{ $attributes->class([
        'rounded-md' => !$round && !$square,
        'rounded-full' => $round,
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.' . $size],
        $colors['background'],
        $colors['text'],
    ]) }}>
    @if ($left)
        {{ $left }}
    @elseif ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::component('icon')"
                             :$icon
                             @class(['mr-1' => $position === 'left', $personalize['icon'], $colors['icon']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($right)
        {{ $right }}
    @elseif ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::component('icon')"
                             :$icon
                             @class(['ml-1' => $position === 'right', $personalize['icon'], $colors['icon']]) />
    @endif
</span>
