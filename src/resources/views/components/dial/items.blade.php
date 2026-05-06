@php
    $customization = $classes();
@endphp

@aware(['position' => 'bottom-right', 'horizontal' => false, 'withoutTooltip' => null, 'square' => null])

<div @class([
    $customization['wrapper.base'],
    $customization['wrapper.horizontal'] => $horizontal && $label && !$withoutTooltip,
])>
    @if ($label && !$withoutTooltip && !$horizontal)
        <span @class([
            $customization['label.tooltip'],
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

    @if ($label && !$withoutTooltip && $horizontal)
        <span @class([$customization['label.inline']])>{{ $label }}</span>
    @endif
</div>
