@php
    $personalize = $classes();
@endphp

<div x-data="{ show: false }" 
     x-on:click.outside="show = false"
     role="menu"
     aria-haspopup="true"
     x-bind:aria-expanded="show">
    <button @class([$personalize['item'], $personalize['border'] => $separator])
            type="button" 
            x-on:click="show = !show" 
            x-ref="button"
            aria-expanded="show">
        @if ($position === 'left-start')
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 :icon="TallStackUi::icon('chevron-left')"
                    @class($personalize['submenu.left']) />
        @endif
        <div @class($personalize['wrapper'])>
            @if ($icon)
                <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class($personalize['icon']) />
            @endif
            {{ $text }}
        </div>
        @if ($position === 'right-start')
        <x-dynamic-component :component="TallStackUi::component('icon')"
                             :icon="TallStackUi::icon('chevron-right')"
                             @class($personalize['submenu.right']) />
        @endif
    </button>
    <x-dynamic-component :component="TallStackUi::component('floating')"
                         :floating="$personalize['floating']"
                         :$position
                         offset="8"
                         x-show="show"
                         x-anchor="$refs.button"
                         role="submenu">
        <x-slot:transition>
            {!! $transitions() !!}
        </x-slot:transition>
        <div @class($personalize['slot'])>
            {!! $slot !!}
        </div>
    </x-dynamic-component>
</div>
