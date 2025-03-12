@php
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else role="menuitem" @endif tabindex="0"
    {{ $attributes->class([
        'gap-x-2' => $icon,
        $personalize['item'],
        $personalize['border'] => $separator,
    ]) }} x-on:click="$refs.dropdown.dispatchEvent(new CustomEvent('select'))">
    @if ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal class="{{ $personalize['icon'] }}" />
    @endif
    {!! $text ?? $slot !!}
    @if ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::prefix('icon')" :$icon internal class="{{ $personalize['icon'] }}" />
    @endif
</{{ $tag }}>
