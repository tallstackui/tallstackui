@php($personalize = $classes())

<div wire:ignore x-data="{ themeSwitch(){ this.$el.dispatchEvent(new CustomEvent('theme', {detail: { darkmode: darkTheme }})); } }">
    <button type="button"
            role="switch"
            aria-checked="false"
            x-on:click="darkTheme = !darkTheme; themeSwitch()"
            {{ $attributes->only('x-on:theme') }}
            @if (!$icons) x-bind:class="{ '{{ $personalize['switch.on'] }}': darkTheme === true, '{{ $personalize['switch.off'] }}': darkTheme === false }" @endif
            @class([$personalize['switch.button'] => !$icons, $personalize['switch.sizes.' . $size] => !$icons])>
         <div @class([
                $personalize['switch.wrapper'] => !$icons, 
                $personalize['switch.icons.sizes.' . $size] => !$icons, 
                $personalize['simple.wrapper'] => $icons,
                $personalize['simple.icons.sizes.' . $size] => $icons,
              ])
              @if (!$icons) x-bind:class="{ '{{ $personalize['switch.translate.' . $size] }}': darkTheme === true, 'translate-x-0': darkTheme === false }" @endif>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-0 duration-100 ease-out': darkTheme === true, 'opacity-100 duration-200 ease-in': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon('sun')"
                                    @class([
                                        $personalize['switch.icons.sizes.' . $size],
                                        $personalize['switch.icons.moon'] => !$icons,
                                        $personalize['simple.icons.sizes.' . $size] => $icons
                                    ]) />
            </span>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-100 duration-200 ease-in': darkTheme === true, 'opacity-0 duration-100 ease-out': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon('moon')"
                                    @class([
                                        $personalize['switch.icons.sizes.' . $size],
                                        $personalize['switch.icons.sun'] => !$icons,
                                        $personalize['simple.icons.sizes.' . $size] => $icons
                                    ]) />
            </span>
        </div>
    </button>
</div>
