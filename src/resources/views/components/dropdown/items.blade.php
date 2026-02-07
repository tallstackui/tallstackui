@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else
    role="menuitem"
@endif tabindex="0"
{{ $attributes->class([
    'gap-x-2' => $icon,
    $customization['item'],
    $customization['border'] => $separator,
]) }} @if ($navigate)
    wire:navigate
@elseif ($navigateHover)
    wire:navigate.hover
@endif x-on:click="$refs.dropdown.dispatchEvent(new CustomEvent('select'))">
@if ($icon && $position === 'left')
    <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal
                         class="{{ $customization['icon'] }}" />
@endif
{!! $text ?? $slot !!}
@if ($icon && $position === 'right')
    <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal
                         class="{{ $customization['icon'] }}" />
@endif
</{{ $tag }}>
