@php($personalize = $classes())

<div wire:ignore>
    <button type="button"
            role="switch"
            aria-checked="false"
            x-on:click="darkTheme = !darkTheme"
            @if (!$simple)
            x-bind:class="{
                'bg-primary-500': darkTheme === true,
                'bg-gray-200': darkTheme === false,
            }"
            @endif
            @class([$personalize['toggle.button'] => !$simple])>
        <div @class([
                $personalize['toggle.wrapper'] => !$simple,
                $personalize['simple.wrapper'] => $simple,
             ])
             @if (!$simple) 
             x-bind:class="{
                'translate-x-5': darkTheme === true,
                'translate-x-0': darkTheme === false,
             }" 
             @endif>
            <span @class($personalize['span'])
                  aria-hidden="true"
                  x-bind:class="{
                      'opacity-0 duration-100 ease-out': darkTheme === true,
                      'opacity-100 duration-200 ease-in': darkTheme === false,
                  }">
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('sun')"
                                     @class([
                                        $personalize['toggle.icon'] => !$simple,
                                        $personalize['simple.icon'] => $simple,
                                     ]) />
            </span>
            <span @class($personalize['span'])
                  aria-hidden="true"
                  x-bind:class="{
                      'opacity-100 duration-200 ease-in': darkTheme === true,
                      'opacity-0 duration-100 ease-out': darkTheme === false,
                  }">
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('moon')"
                                     @class([
                                        $personalize['toggle.icon'] => !$simple,
                                        $personalize['simple.icon'] => $simple,
                                     ]) />
            </span>
        </div>
    </button>
</div>
