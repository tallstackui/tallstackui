@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_backToTop(@js($anchor), @js(!$immediate))"
     @class([$customization['position.'.$position]])
     {{ $attributes }}>
    <button type="button"
            dusk="tallstackui_back_to_top"
            x-show="show"
            x-on:click="scroll()"
            @if (!$ts_ui__flash)
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-75"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-75"
            @endif
            aria-label="Back to top"
            @class([
                'rounded-full' => !$square,
                'rounded-lg' => $square,
                $colors['background'],
                $customization['button.base'],
                $customization['button.sizes.'.$size],
            ])>
        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                             :icon="TallStackUi::icon($icon)"
                             internal
                             @class([$customization['icon.sizes.'.$size], $colors['icon']]) />
    </button>
</div>
