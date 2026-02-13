<div wire:ignore.self
     x-cloak
     x-data="{
        setAs(type) {
            mode = type;

            this.$el.dispatchEvent(new CustomEvent('theme', {
                detail: {
                    darkTheme: darkTheme,
                    mode: mode
                }
            }));
        }
     }">
    <div @class([$customization['segmented.wrapper'], 'w-full' => $block]) {{ $attributes->only('x-on:change') }}>
        <button type="button"
                x-on:click="setAs('dark')"
                x-bind:class="{
                    '{{ $customization['segmented.active'] }}': mode === 'dark',
                    '{{ $customization['segmented.inactive'] }}': mode !== 'dark'
                }"
                @class([$customization['segmented.button'], 'flex flex-1 items-center justify-center focus:outline-hidden' => $block])>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('moon')"
                                 internal
                                 @class([$customization['segmented.colors.moon'], $customization['segmented.icons.sizes.' . $size]]) />
        </button>
        <button type="button"
                x-on:click="setAs('system')"
                x-bind:class="{
                    '{{ $customization['segmented.active'] }}': mode === 'system',
                    '{{ $customization['segmented.inactive'] }}': mode !== 'system'
                }"
                @class([$customization['segmented.button'], 'flex flex-1 items-center justify-center focus:outline-hidden' => $block])>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('computer-desktop')"
                                 internal
                                 @class([$customization['segmented.colors.system'], $customization['segmented.icons.sizes.' . $size]]) />
        </button>
        <button type="button"
                x-on:click="setAs('light')"
                x-bind:class="{
                    '{{ $customization['segmented.active'] }}': mode === 'light',
                    '{{ $customization['segmented.inactive'] }}': mode !== 'light'
                }"
                @class([$customization['segmented.button'], 'flex flex-1 items-center justify-center focus:outline-hidden' => $block])>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('sun')"
                                 internal
                                 @class([$customization['segmented.colors.sun'], $customization['segmented.icons.sizes.' . $size]]) />
        </button>
    </div>
</div>
