@php
    $customization = $classes();
@endphp

@aware(['position' => 'bottom-right', 'withoutTooltip' => null, 'square' => null])

<div class="relative flex items-center">
    @if ($label && !$withoutTooltip)
        <span @class([
            $customization['label'],
            'right-full mr-2' => str_contains($position, 'right'),
            'left-full ml-2' => str_contains($position, 'left'),
        ])>{{ $label }}</span>
    @endif
    <{{ $tag }} @if ($href) href="{{ $href }}" @endif
        @if ($navigate) wire:navigate @elseif ($navigateHover) wire:navigate.hover @endif
        {{ $attributes->class([
            $customization['item'],
            'rounded-full' => !$square,
        ]) }}
        dusk="tallstackui_dial_item">
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :$icon
                             internal
                             class="{{ $customization['icon'] }}" />
    </{{ $tag }}>
</div>
