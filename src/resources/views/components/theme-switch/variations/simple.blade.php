<div wire:ignore.self
     x-cloak
     x-data="{
        themeSwitch() {
            this.$el.dispatchEvent(new CustomEvent('theme', {
                detail: {
                    darkTheme: darkTheme,
                    mode: mode
                }
            }));
        }
     }">
    <button type="button"
            role="switch"
            x-bind:aria-checked="darkTheme.toString()"
            x-on:click="mode = darkTheme ? 'light' : 'dark'; themeSwitch()"
            {{ $attributes->only('x-on:change') }}
            @if (!$onlyIcons)
                x-bind:class="{
                    '{{ $customization['switch.on'] }}': darkTheme === true,
                    '{{ $customization['switch.off'] }}': darkTheme === false
                }"
            @endif
            @class([$customization['button'], $customization['switch.button'] => !$onlyIcons, $customization['switch.sizes.' . $size] => !$onlyIcons])>
        <div @class([
                $customization['switch.wrapper'] => !$onlyIcons,
                $customization['switch.icons.sizes.' . $size] => !$onlyIcons,
                $customization['simple.wrapper'] => $onlyIcons,
                $customization['simple.icons.sizes.' . $size] => $onlyIcons,
             ])
             @if (!$onlyIcons)
                 x-bind:class="{
                     '{{ $customization['switch.translate.' . $size] }}': darkTheme === true,
                     'translate-x-0': darkTheme === false
                 }"
                @endif>
            <span class="{{ $customization['wrapper'] }}"
                  aria-hidden="true"
                  x-bind:class="{
                      'opacity-0 duration-100 ease-out': darkTheme === true,
                      'opacity-100 duration-200 ease-in': darkTheme === false
                  }">
               <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                    :icon="TallStackUi::icon($onlyIcons ? 'moon' : 'sun')"
                                    internal
                                    @class([
                                        $customization['colors.moon'] => !$onlyIcons,
                                        $customization['colors.sun'] => $onlyIcons,
                                        $customization['switch.icons.sizes.' . $size],
                                        $customization['simple.icons.sizes.' . $size] => $onlyIcons
                                    ]) />
            </span>
            <span class="{{ $customization['wrapper'] }}"
                  aria-hidden="true"
                  x-bind:class="{
                      'opacity-100 duration-200 ease-in': darkTheme === true,
                      'opacity-0 duration-100 ease-out': darkTheme === false
                  }">
               <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                    :icon="TallStackUi::icon($onlyIcons ? 'sun' : 'moon')"
                                    internal
                                    @class([
                                        $customization['colors.sun'] => !$onlyIcons,
                                        $customization['colors.moon'] => $onlyIcons,
                                        $customization['switch.icons.sizes.' . $size],
                                        $customization['simple.icons.sizes.' . $size] => $onlyIcons
                                    ]) />
            </span>
        </div>
    </button>
</div>
