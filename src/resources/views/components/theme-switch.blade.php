@php($personalize = $classes())

<div wire:ignore>
    <button type="button"
            role="switch"
            aria-checked="false"
            x-on:click="darkTheme = !darkTheme"
            @if (!$icons) x-bind:class="{ 'bg-primary-500': darkTheme === true, 'bg-gray-200': darkTheme === false }" @endif
            @class([$personalize['switch.button'] => !$icons])>
         <div @class([$personalize['switch.wrapper'] => !$icons, $personalize['simple.wrapper'] => $icons])
              @if (!$icons) x-bind:class="{ 'translate-x-5': darkTheme === true, 'translate-x-0': darkTheme === false }"@endif>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-0 duration-100 ease-out': darkTheme === true, 'opacity-100 duration-200 ease-in': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon('sun')"
                                    @class([
                                        $personalize['switch.icons.size'],
                                        $personalize['switch.icons.moon'] => !$icons,
                                        $personalize['simple.icon'] => $icons
                                    ]) />
            </span>
            <span @class($personalize['wrapper'])
                  aria-hidden="true"
                  x-bind:class="{ 'opacity-100 duration-200 ease-in': darkTheme === true, 'opacity-0 duration-100 ease-out': darkTheme === false }">
               <x-dynamic-component :component="TallStackUi::component('icon')"
                                    :icon="TallStackUi::icon('moon')"
                                    @class([
                                        $personalize['switch.icons.size'],
                                        $personalize['switch.icons.sun'] => !$icons,
                                        $personalize['simple.icon'] => $icons
                                    ]) />
            </span>
        </div>
    </button>
</div>
