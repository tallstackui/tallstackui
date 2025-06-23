@php
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else role="menuitem" @endif tabindex="0"
    {{ $attributes->class([
        $personalize['gap'] => $icon || $loading,
        $personalize['item'],
        $personalize['border'] => $separator,
    ]) }} 
    @if ($loading) 
        wire:loading.attr="disabled" 
        wire:loading.class="{{ $personalize['loading.disabled'] }}"
    @endif
    x-on:click="$refs.dropdown.dispatchEvent(new CustomEvent('select'))">
    
    @if ($loading && $position === 'left')
        <x-tallstack-ui::icon.generic.loading-button 
            :$loading 
            @class([
                $personalize['loading.icon'],
            ]) />
    @endif
    
    @if ($icon && $position === 'left')
        <x-dynamic-component 
            :component="TallStackUi::prefix('icon')" 
            :$icon 
            internal 
            class="{{ $personalize['icon'] }}" />
    @endif
    
    {!! $text ?? $slot !!}
    
    @if ($icon && $position === 'right')
        <x-dynamic-component 
            :component="TallStackUi::prefix('icon')" 
            :$icon 
            internal 
            class="{{ $personalize['icon'] }}" />
    @endif
    
    @if ($loading && $position === 'right')
        <x-tallstack-ui::icon.generic.loading-button 
            :$loading 
            @class([
                $personalize['loading.icon'],
            ]) />
    @endif
</{{ $tag }}>
