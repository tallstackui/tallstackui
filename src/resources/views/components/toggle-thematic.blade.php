@php($personalize = $classes())

<button x-data="{style: @js($style)}" 
        type="button" 
        x-on:click="darkTheme = !darkTheme" 
        @class([
            $personalize['wrapper.first'],
            'w-12' => $style === "toggle",
        ])>
    @if($style === "toggle")
        <div :class="{ 'translate-x-full': darkTheme }"
            @class($personalize['switch'])>
        </div>
    @endif
    <div @class([$personalize['wrapper.second'] => $style === "toggle"])>
        <x-dynamic-component :component="TallStackUi::component('icon')" 
                             :icon="$personalize['icons.dark.name']"
                             x-show="style === 'simple'  && darkTheme || style === 'toggle'"
                             @class([
                                $personalize['icons.dark.size'], 
                                $personalize['icons.dark.color'] => !$colorful,
                                $personalize['icons.dark.colorful'] => $colorful,
                             ]) />
        <x-dynamic-component :component="TallStackUi::component('icon')" 
                             :icon="$personalize['icons.light.name']"
                             x-show="style === 'simple' && !darkTheme || style === 'toggle'"
                             @class([
                                $personalize['icons.light.size'], 
                                $personalize['icons.light.color'] => !$colorful,
                                $personalize['icons.light.colorful'] => $colorful,
                             ]) />
    </div>
</button>