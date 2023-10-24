@php
    $tag = $href ? 'a' : 'button';
    $customize = tallstackui_personalization('button', $personalization());

    $sizes['wrapper'] = $customize['wrapper.sizes.' . $size];
    $sizes['icon'] = $customize['icon.sizes.' . $size];
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else
    role="button"
@endif {{ $attributes->class([
        $customize['wrapper.class'],
        $sizes['wrapper'],
        $colors['wrapper.color'],
        'rounded' => !$square && !$round,
        'rounded-full' => !$square && $round !== null,
    ]) }} wire:loading.attr="disabled" wire:loading.class="!cursor-wait">
    @if ($icon && $position === 'left')
        <x-icon :$icon @class([$sizes['icon'], $colors['icon.color']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($icon && $position === 'right')
        <x-icon :$icon @class([$sizes['icon'], $colors['icon.color']]) />
    @endif
    @if ($loading)
        <x-tallstack-ui::icon.others.loading-button :$loading :$delay @class([
            'animate-spin',
            $sizes['icon'],
            $colors['icon.loading.color'],
        ]) />
    @endif
</{{ $tag }}>
