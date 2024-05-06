@php
    $personalize = $classes();
@endphp

<div wire:ignore x-cloak x-data="{ themeSwitch() { this.$el.dispatchEvent(new CustomEvent('theme', {detail: { darkTheme: darkTheme }})); } }">
    <button type="button"
            role="switch"
            aria-checked="false"
            x-on:click="darkTheme = !darkTheme; themeSwitch()"
            {{ $attributes->only('x-on:change') }}
            @if (!$onlyIcons) x-bind:class="{ '{{ $personalize['switch.on'] }}': darkTheme === true, '{{ $personalize['switch.off'] }}': darkTheme === false }" @endif
            @class([$personalize['button'], $personalize['switch.button'] => !$onlyIcons, $personalize['switch.sizes.' . $size] => !$onlyIcons])>
         <div @class([
                $personalize['switch.wrapper'] => !$onlyIcons, 
                $personalize['switch.icons.sizes.' . $size] => !$onlyIcons, 
                $personalize['simple.wrapper'] => $onlyIcons,
                $personalize['simple.icons.sizes.' . $size] => $onlyIcons,
              ])
              @if (!$onlyIcons) x-bind:class="{ '{{ $personalize['switch.translate.' . $size] }}': darkTheme === true, 'translate-x-0': darkTheme === false }" @endif>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-0 duration-100 ease-out': darkTheme === true, 'opacity-100 duration-200 ease-in': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon($onlyIcons ? 'moon' : 'sun')"
                                    @class([
                                        $personalize['colors.moon'] => !$onlyIcons,
                                        $personalize['colors.sun'] => $onlyIcons,
                                        $personalize['switch.icons.sizes.' . $size],
                                        $personalize['simple.icons.sizes.' . $size] => $onlyIcons
                                    ]) />
            </span>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-100 duration-200 ease-in': darkTheme === true, 'opacity-0 duration-100 ease-out': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon($onlyIcons ? 'sun' : 'moon')"
                                    @class([
                                        $personalize['colors.sun'] => !$onlyIcons,
                                        $personalize['colors.moon'] => $onlyIcons,
                                        $personalize['switch.icons.sizes.' . $size],
                                        $personalize['simple.icons.sizes.' . $size] => $onlyIcons
                                    ]) />
            </span>
        </div>
    </button>
</div>
